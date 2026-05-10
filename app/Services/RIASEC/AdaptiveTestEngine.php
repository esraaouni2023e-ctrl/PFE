<?php

namespace App\Services\RIASEC;

use App\Models\AnswerRiasec;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use App\Models\RiasecTestSession;
use App\Services\RIASEC\DTO\RiasecScoreDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * AdaptiveTestEngine — Moteur de test RIASEC adaptatif (CAT simplifié).
 *
 * ┌─────────────────────────────────────────────────────────────────┐
 * │  ALGORITHME GLOBAL                                              │
 * │                                                                 │
 * │  Phase 1 — SEED (12 questions, 2/dimension)                    │
 * │    Difficulté médiane (≈3.0) pour initialiser θ                │
 * │                                                                 │
 * │  Phase 2 — ADAPTIVE (IRT 2-PL simplifié)                       │
 * │    Après chaque réponse :                                       │
 * │      1. Recalculer θ_d pour chaque dimension d                  │
 * │      2. Calculer priorité_d (HIGH/MED/LOW) selon rang           │
 * │      3. Pour chaque question candidate Q :                      │
 * │           score(Q) = I(θ_d, Q) × priorité_d × bonus_couverture │
 * │      4. Sélectionner argmax(score)                              │
 * │                                                                 │
 * │  Critère d'arrêt (shouldTerminate) :                           │
 * │    Hard  : total ≥ MAX_QUESTIONS (55)                           │
 * │    Soft  : total ≥ MIN_QUESTIONS (30)                           │
 * │          + chaque dim ≥ MIN_PER_DIM (5)                        │
 * │          + variance(scores[-6:]) < PRECISION_THR               │
 * └─────────────────────────────────────────────────────────────────┘
 *
 * Référence : Lord (1980) — Applications of Item Response Theory
 */
class AdaptiveTestEngine
{
    // ── Paramètres du moteur ───────────────────────────────────────────────
    const SEED_PER_DIM       = 2;   // Questions seed par dimension
    const MIN_QUESTIONS      = 30;  // Seuil minimal avant arrêt anticipé
    const MAX_QUESTIONS      = 55;  // Plafond absolu
    const MIN_PER_DIM        = 5;   // Minimum par dimension en fin de test
    const MAX_PER_DIM        = 12;  // Maximum par dimension (évite la sur-représentation)
    const PRECISION_THR      = 3.5; // Variance max acceptable pour arrêt (%)
    const SEPARATION_GAP     = 25.0;// Gap top3/bottom3 pour arrêt précoce (%)
    const RECALC_EVERY       = 4;   // Recalcul scores tous les N réponses

    // ── Poids de priorité des dimensions ──────────────────────────────────
    const W_HIGH        = 1.6; // Top 2 dimensions (confirmer la dominance)
    const W_MED_HIGH    = 1.2; // Dans les 12% du top (départager)
    const W_MED         = 0.7; // Zone médiane
    const W_LOW         = 0.25;// Dimensions clairement faibles

    // ── Dimensions Holland ─────────────────────────────────────────────────
    const DIMS = ['R', 'I', 'A', 'S', 'E', 'C'];

    public function __construct(private readonly TestManager $testManager) {}

    // ══════════════════════════════════════════════════════════════════════
    // 1. DÉMARRAGE DU TEST
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Initialise une nouvelle session de test et retourne les métadonnées.
     *
     * @param  int|null $userId
     * @param  array    $demographicData  ['age'=>18,'bac_type'=>'S','region'=>'Tunis']
     * @return array{session: RiasecTestSession, first_question: ?QuestionRiasec}
     */
    public function startTest(?int $userId, array $demographicData = []): array
    {
        $session = RiasecTestSession::create([
            'session_token'             => (string) Str::uuid(),
            'user_id'                   => $userId,
            'session_guest_id'          => $userId ? null : session()->getId(),
            'demographic_data'          => $demographicData ?: null,
            'current_scores'            => array_fill_keys(self::DIMS, 50.0),
            'administered_question_ids' => null,
            'phase'                     => RiasecTestSession::PHASE_SEED,
            'seed_phase_complete'       => false,
            'total_questions_asked'     => 0,
            'min_questions'             => self::MIN_QUESTIONS,
            'max_questions'             => self::MAX_QUESTIONS,
            'statut'                    => RiasecTestSession::STATUT_EN_COURS,
            'started_at'                => now(),
        ]);

        $firstQuestion = $this->getNextQuestion($session->session_token);

        return [
            'session'        => $session,
            'first_question' => $firstQuestion,
            'session_token'  => $session->session_token,
        ];
    }

    // ══════════════════════════════════════════════════════════════════════
    // 2. SÉLECTION DE LA PROCHAINE QUESTION (cœur adaptatif)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Retourne la prochaine question optimale pour la session donnée.
     *
     * ┌──────────────────────────────────────────────────────────────┐
     * │ ALGORITHME getNextQuestion()                                 │
     * │                                                              │
     * │ PHASE SEED :                                                 │
     * │   Pour chaque dimension d dans [R,I,A,S,E,C] :              │
     * │     si seedCount[d] < SEED_PER_DIM :                        │
     * │       → sélectionner la question de d la + proche de diff=3  │
     * │   Quand seedCount[d]=2 pour tous → passer en PHASE ADAPTIVE  │
     * │                                                              │
     * │ PHASE ADAPTIVE (IRT Maximum Information) :                   │
     * │   1. Charger scores courants θ = {R:60,I:45,...}            │
     * │   2. Calculer priorités P[d] selon rang de θ[d]             │
     * │   3. Charger toutes questions actives non posées             │
     * │   4. Pour chaque question Q(dim=d, diff=b, disc=a) :        │
     * │        θ_norm = θ[d]/100 * 4          (map 0-100 → 0-4)     │
     * │        b_norm = (b-1)/4 * 4           (map 1-5 → 0-4)       │
     * │        P(θ)   = σ(a·(θ_norm - b_norm)) (logistique)         │
     * │        I(θ)   = a² · P(θ) · (1-P(θ))  (info IRT 2PL)       │
     * │        bonus  = 1.4 si count[d] < MIN_PER_DIM               │
     * │        score  = I(θ) · P[d] · bonus                         │
     * │   5. argmax(score) → question sélectionnée                  │
     * └──────────────────────────────────────────────────────────────┘
     */
    public function getNextQuestion(string $sessionToken): ?QuestionRiasec
    {
        $session = RiasecTestSession::where('session_token', $sessionToken)->firstOrFail();

        if ($session->isTerminated()) return null;

        $administered = $session->administered_question_ids ?? [];
        $dimCounts    = $this->getDimCounts($sessionToken, $administered);

        // ── Phase SEED ────────────────────────────────────────────────────
        if ($session->isInSeedPhase()) {
            return $this->selectSeedQuestion($session, $administered, $dimCounts);
        }

        // ── Phase ADAPTIVE ────────────────────────────────────────────────
        return $this->selectAdaptiveQuestion($session, $administered, $dimCounts);
    }

    // ── Phase 1 : Seed ────────────────────────────────────────────────────

    private function selectSeedQuestion(
        RiasecTestSession $session,
        array $administered,
        array $dimCounts
    ): ?QuestionRiasec {
        // Trouver la première dimension manquant encore ses questions seed
        foreach (self::DIMS as $dim) {
            if (($dimCounts[$dim] ?? 0) >= self::SEED_PER_DIM) continue;

            // Question seed : is_seed=true OU difficulté la plus proche de 3.0
            $question = QuestionRiasec::where('dimension', $dim)
                ->where('actif', true)
                ->whereNotIn('id', $administered ?: [0])
                ->orderByRaw('ABS(difficulty - 3.0) ASC')
                ->first();

            if ($question) return $question;
        }

        // Toutes les dimensions ont leurs seeds → transition vers adaptive
        $session->update([
            'phase'               => RiasecTestSession::PHASE_ADAPTIVE,
            'seed_phase_complete' => true,
        ]);

        return $this->selectAdaptiveQuestion($session, $administered, $dimCounts);
    }

    // ── Phase 2 : Adaptive (IRT 2PL Maximum Information) ─────────────────

    private function selectAdaptiveQuestion(
        RiasecTestSession $session,
        array $administered,
        array $dimCounts
    ): ?QuestionRiasec {
        $currentScores = $session->current_scores ?? array_fill_keys(self::DIMS, 50.0);
        $priorities    = $this->calculatePriorities($currentScores, $dimCounts);

        // Charger toutes les questions éligibles (actives, non posées, dim non saturée)
        $candidates = QuestionRiasec::where('actif', true)
            ->whereNotIn('id', $administered ?: [0])
            ->whereIn('dimension', $this->getEligibleDims($dimCounts))
            ->select(['id', 'dimension', 'difficulty', 'discrimination', 'is_reverse', 'poids'])
            ->get();

        if ($candidates->isEmpty()) return null;

        // Calculer le score IRT pour chaque candidat
        $best      = null;
        $bestScore = -1.0;

        foreach ($candidates as $q) {
            $dim     = $q->dimension;
            $theta   = ($currentScores[$dim] ?? 50.0) / 100.0 * 4.0; // 0-100 → 0-4
            $irtInfo = $this->irtInformation($theta, $q->difficulty, $q->discrimination ?? 1.0);

            // Bonus couverture : booster dimensions pas encore à MIN_PER_DIM
            $coverageBonus = ($dimCounts[$dim] ?? 0) < self::MIN_PER_DIM ? 1.4 : 1.0;

            // Score pondéré final
            $score = $irtInfo * ($priorities[$dim] ?? self::W_MED) * $coverageBonus * $q->poids;

            // Léger bruit aléatoire pour éviter les répétitions déterministes
            $score += mt_rand(0, 100) / 10000.0;

            if ($score > $bestScore) {
                $bestScore = $score;
                $best      = $q;
            }
        }

        return $best ? QuestionRiasec::find($best->id) : null;
    }

    // ══════════════════════════════════════════════════════════════════════
    // 3. ENREGISTREMENT D'UNE RÉPONSE
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Enregistre une réponse, met à jour la session et déclenche le recalcul.
     *
     * @return array{session: RiasecTestSession, terminate: array, next_question: ?QuestionRiasec}
     */
    public function submitAnswer(
        string $sessionToken,
        int    $questionId,
        int    $score,
        ?int   $tempsMs = null
    ): array {
        $session = RiasecTestSession::where('session_token', $sessionToken)->firstOrFail();

        if ($session->isTerminated()) {
            return ['session' => $session, 'terminate' => ['should_stop' => true, 'reason' => 'already_terminated'], 'next_question' => null];
        }

        $question = QuestionRiasec::findOrFail($questionId);
        $guestId  = $session->user_id ? null : $session->session_guest_id;

        // Valeur effective (inversion si question inversée)
        $effectiveScore = ($question->is_reverse ?? false)
            ? (6 - $score)
            : $score;

        // Sauvegarder la réponse dans riasec_answers
        AnswerRiasec::enregistrer(
            sessionId:  $sessionToken,
            questionId: $questionId,
            valeur:     $score,
            userId:     $session->user_id,
            guestId:    $guestId,
            tempsMs:    $tempsMs
        );

        // Marquer la question comme administrée
        $session->markQuestionAsked($questionId);

        // Recalcul des scores tous les RECALC_EVERY réponses (ou à la dernière)
        if ($session->total_questions_asked % self::RECALC_EVERY === 0) {
            $newScores = $this->calculateProvisionalScores($sessionToken);
            $session->appendScoreHistory($newScores);
            $variance  = $session->calculateScoreVariance();

            $session->update([
                'current_scores'            => $newScores,
                'administered_question_ids' => $session->administered_question_ids,
                'total_questions_asked'     => $session->total_questions_asked,
                'scores_history'            => $session->scores_history,
                'score_variance'            => $variance,
                'precision_score'           => max(0, 100 - ($variance * 10)),
            ]);
        } else {
            $session->update([
                'administered_question_ids' => $session->administered_question_ids,
                'total_questions_asked'     => $session->total_questions_asked,
            ]);
        }

        $session->refresh();

        $terminate     = $this->shouldTerminateTest($session);
        $nextQuestion  = $terminate['should_stop'] ? null : $this->getNextQuestion($sessionToken);

        return compact('session', 'terminate', 'next_question');
    }

    // ══════════════════════════════════════════════════════════════════════
    // 4. CALCUL DES SCORES PROVISOIRES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Calcule les 6 scores normalisés (0-100) à partir des réponses actuelles.
     *
     * @return array<string, float>  ['R'=>72.5, 'I'=>45.0, ...]
     */
    public function calculateProvisionalScores(string $sessionToken): array
    {
        $answers = AnswerRiasec::session($sessionToken)
            ->avecQuestion()
            ->get();

        $raw = array_fill_keys(self::DIMS, 0.0);
        $max = array_fill_keys(self::DIMS, 0.0);

        foreach ($answers as $answer) {
            $q = $answer->question;
            if (! $q || ! in_array($q->dimension, self::DIMS)) continue;

            $dim    = $q->dimension;
            $valeur = ($q->is_reverse ?? false) ? (6 - $answer->valeur) : $answer->valeur;

            $raw[$dim] += $valeur * ($q->poids ?? 1);
            $max[$dim] += 5 * ($q->poids ?? 1);
        }

        $normalized = [];
        foreach (self::DIMS as $dim) {
            $normalized[$dim] = $max[$dim] > 0
                ? round(($raw[$dim] / $max[$dim]) * 100, 2)
                : 50.0; // Valeur neutre si aucune réponse pour cette dimension
        }

        return $normalized;
    }

    // ══════════════════════════════════════════════════════════════════════
    // 5. CRITÈRE D'ARRÊT
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Détermine si le test doit s'arrêter.
     *
     * Critères (par ordre de priorité) :
     *   1. Hard stop  : total_questions_asked ≥ MAX_QUESTIONS
     *   2. Soft stop  : total ≥ MIN_QUESTIONS
     *                 + chaque dim ≥ MIN_PER_DIM
     *                 + variance < PRECISION_THR
     *   3. Séparation : gap top3 vs bottom3 > SEPARATION_GAP et total ≥ MIN_QUESTIONS
     *
     * @return array{should_stop:bool, reason:string|null, precision:float}
     */
    public function shouldTerminateTest(RiasecTestSession|string $session): array
    {
        if (is_string($session)) {
            $session = RiasecTestSession::where('session_token', $session)->firstOrFail();
        }

        $total    = $session->total_questions_asked;
        $variance = $session->score_variance ?? 99.0;
        $scores   = $session->current_scores ?? array_fill_keys(self::DIMS, 50.0);

        // ── 1. Hard stop ──────────────────────────────────────────────────
        if ($total >= self::MAX_QUESTIONS) {
            return ['should_stop' => true, 'reason' => RiasecTestSession::STOP_MAX_REACHED, 'precision' => 100.0];
        }

        // Pas encore assez de questions
        if ($total < self::MIN_QUESTIONS) {
            return ['should_stop' => false, 'reason' => null, 'precision' => max(0, 100 - $variance * 10)];
        }

        $dimCounts = $this->getDimCounts($session->session_token, $session->administered_question_ids ?? []);

        // Vérifier la couverture minimale par dimension
        $minCoverage = min(array_values($dimCounts));
        if ($minCoverage < self::MIN_PER_DIM) {
            return ['should_stop' => false, 'reason' => null, 'precision' => max(0, 100 - $variance * 10)];
        }

        // ── 2. Soft stop par précision ────────────────────────────────────
        if ($variance <= self::PRECISION_THR) {
            return ['should_stop' => true, 'reason' => RiasecTestSession::STOP_PRECISION_ACHIEVED, 'precision' => max(0, 100 - $variance * 10)];
        }

        // ── 3. Arrêt par séparation nette (gap top3 / bottom3) ────────────
        arsort($scores);
        $values = array_values($scores);
        $top3avg    = ($values[0] + $values[1] + $values[2]) / 3;
        $bottom3avg = ($values[3] + $values[4] + $values[5]) / 3;

        if (($top3avg - $bottom3avg) >= self::SEPARATION_GAP) {
            return ['should_stop' => true, 'reason' => RiasecTestSession::STOP_MIN_DIMS_MET, 'precision' => max(0, 100 - $variance * 10)];
        }

        return ['should_stop' => false, 'reason' => null, 'precision' => max(0, 100 - $variance * 10)];
    }

    // ══════════════════════════════════════════════════════════════════════
    // 6. GÉNÉRATION DU PROFIL FINAL
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Finalise le test : calcule les scores définitifs, génère le profil Holland
     * et marque la session comme complète.
     */
    public function generateFinalProfile(string $sessionToken): ProfileRiasec
    {
        $session   = RiasecTestSession::where('session_token', $sessionToken)->firstOrFail();
        $scores    = $this->calculateProvisionalScores($sessionToken);
        $terminate = $this->shouldTerminateTest($session);

        // Déterminer le trigramme (avec départage Holland R>I>A>S>E>C)
        $trigram = $this->testManager->determineDominantTrigram($scores);

        // Cohérence finale
        $coherence = $this->calculateCoherenceScore($sessionToken);

        // Interprétation textuelle
        $scoreDTO = new \App\Services\RIASEC\DTO\RiasecScoreDTO(
            rawScores:        array_map(fn ($s) => (int) round($s), $scores),
            normalizedScores: $scores,
            trigram:          $trigram,
            totalAnswers:     $session->total_questions_asked,
            coherenceScore:   (int) $coherence,
        );
        $interpretation = $this->testManager->generateInterpretation($scoreDTO);

        // Mise à jour de la session
        $session->update([
            'current_scores'  => $scores,
            'coherence_score' => $coherence,
            'statut'          => RiasecTestSession::STATUT_COMPLET,
            'stop_reason'     => $terminate['reason'] ?? RiasecTestSession::STOP_PRECISION_ACHIEVED,
            'precision_score' => $terminate['precision'] ?? null,
            'completed_at'    => now(),
        ]);

        // Création du ProfileRiasec
        return ProfileRiasec::updateOrCreate(
            ['test_session_id' => $sessionToken],
            [
                'user_id'                => $session->user_id,
                'session_guest_id'       => $session->session_guest_id,
                'score_r'                => (int) round($scores['R']),
                'score_i'                => (int) round($scores['I']),
                'score_a'                => (int) round($scores['A']),
                'score_s'                => (int) round($scores['S']),
                'score_e'                => (int) round($scores['E']),
                'score_c'                => (int) round($scores['C']),
                'code_holland'           => $trigram,
                'statut'                 => ProfileRiasec::STATUT_COMPLET,
                'nb_questions_repondues' => $session->total_questions_asked,
                'nb_questions_total'     => $session->total_questions_asked,
                'score_coherence'        => (int) $coherence,
                'interpretation'         => $interpretation,
                'complete_at'            => now(),
            ]
        );
    }

    // ══════════════════════════════════════════════════════════════════════
    // MÉTHODES PRIVÉES UTILITAIRES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Fonction d'information IRT 2-PL.
     *
     * Formule :
     *   P(θ) = 1 / (1 + exp(-a × (θ - b)))
     *   I(θ) = a² × P(θ) × (1 - P(θ))
     *
     * @param float $theta  Niveau de trait estimé pour la dimension (0-4)
     * @param float $b      Difficulté de la question (1-5 → converti en 0-4)
     * @param float $a      Discrimination de la question (0.5-3.0)
     */
    private function irtInformation(float $theta, float $b, float $a): float
    {
        $bNorm = (($b - 1.0) / 4.0) * 4.0; // Convertit 1-5 → 0-4
        $exp   = exp(-$a * ($theta - $bNorm));
        $P     = 1.0 / (1.0 + $exp);
        return $a * $a * $P * (1.0 - $P);
    }

    /**
     * Calcule les priorités de chaque dimension selon les scores courants.
     *
     * Logique de classement :
     *   Rang 0-1  (top 2)           → W_HIGH       (confirmer la dominance)
     *   Dans 12% du score top       → W_MED_HIGH    (départager)
     *   Dans 30% du score top       → W_MED         (couverture standard)
     *   > 30% sous le top           → W_LOW         (clairement faible)
     *
     * @param  array<string,float> $scores    Scores courants [R=>60,I=>45,...]
     * @param  array<string,int>   $dimCounts Nombre de réponses par dimension
     * @return array<string,float>
     */
    private function calculatePriorities(array $scores, array $dimCounts): array
    {
        arsort($scores);
        $ranked   = array_keys($scores);
        $topScore = max($scores) ?: 1.0;
        $priorities = [];

        foreach ($ranked as $rank => $dim) {
            $gap = ($topScore - $scores[$dim]) / $topScore * 100;

            $priorities[$dim] = match(true) {
                $rank < 2         => self::W_HIGH,
                $gap <= 12.0      => self::W_MED_HIGH,
                $gap <= 30.0      => self::W_MED,
                default           => self::W_LOW,
            };

            // Surboost si dimension très peu couverte (garantit MIN_PER_DIM)
            if (($dimCounts[$dim] ?? 0) < self::MIN_PER_DIM) {
                $priorities[$dim] = max($priorities[$dim], self::W_MED_HIGH);
            }
        }

        return $priorities;
    }

    /**
     * Retourne les dimensions éligibles (non saturées, pas encore à MAX_PER_DIM).
     *
     * @return string[]
     */
    private function getEligibleDims(array $dimCounts): array
    {
        return array_filter(
            self::DIMS,
            fn ($d) => ($dimCounts[$d] ?? 0) < self::MAX_PER_DIM
        );
    }

    /**
     * Compte le nombre de réponses par dimension pour une session.
     *
     * @return array<string, int>  ['R'=>3, 'I'=>2, ...]
     */
    private function getDimCounts(string $sessionToken, array $administered): array
    {
        if (empty($administered)) {
            return array_fill_keys(self::DIMS, 0);
        }

        $counts = AnswerRiasec::session($sessionToken)
            ->avecQuestion()
            ->get()
            ->groupBy(fn ($a) => $a->question?->dimension)
            ->map(fn ($g) => $g->count())
            ->all();

        return array_merge(array_fill_keys(self::DIMS, 0), $counts);
    }

    /**
     * Calcule le score de cohérence des réponses (0-100).
     * Compare les questions inversées avec les directes dans chaque dimension.
     */
    private function calculateCoherenceScore(string $sessionToken): float
    {
        $answers = AnswerRiasec::session($sessionToken)->avecQuestion()->get();

        if ($answers->count() < 6) return 75.0;

        $variances = [];

        foreach (self::DIMS as $dim) {
            $dimAnswers = $answers->filter(fn ($a) => $a->question?->dimension === $dim);
            if ($dimAnswers->count() < 2) continue;

            $values = $dimAnswers->map(fn ($a) =>
                ($a->question->is_reverse ?? false) ? (6 - $a->valeur) : $a->valeur
            );

            $mean      = $values->avg();
            $variance  = $values->map(fn ($v) => ($v - $mean) ** 2)->avg();
            $variances[] = $variance;
        }

        if (empty($variances)) return 80.0;

        $avgVar = array_sum($variances) / count($variances);
        return max(0, min(100, round(100 - ($avgVar / 4.0) * 100)));
    }
}
