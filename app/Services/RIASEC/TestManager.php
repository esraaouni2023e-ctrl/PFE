<?php

namespace App\Services\RIASEC;

use App\Models\AnswerRiasec;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use App\Services\RIASEC\DTO\RiasecScoreDTO;
use App\Services\RIASEC\DTO\TestProgressDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * TestManager v5.0 — Service central du module RIASEC.
 *
 * Responsabilités :
 *  1. Chargement des questions
 *  2. Enregistrement des réponses
 *  3. Calcul des scores RIASEC (uniquement Holland R/I/A/S/E/C)
 *  4. Calcul des scores GATB objectifs et Résilience
 *  5. Détermination du trigramme dominant
 *  6. Vérification de la cohérence interne
 *  7. Génération de l'interprétation
 *  8. Gestion de la progression et du profil
 *
 * Architecture v5.0 :
 *  - Trigramme Holland = uniquement 6 dimensions RIASEC
 *  - Cohérence = calculée uniquement sur RIASEC
 *  - GATB, Résilience, Attention = blocs dédiés, stockés séparément dans riasec_profiles
 */
class TestManager
{
    // ── Ordre de priorité Holland pour le départage en cas d'égalité ──────
    private const HOLLAND_PRIORITY = ['R' => 0, 'I' => 1, 'A' => 2, 'S' => 3, 'E' => 4, 'C' => 5];

    // ── Dimensions RIASEC pures (Holland) ─────────────────────────────────
    private const RIASEC_DIMS = ['R', 'I', 'A', 'S', 'E', 'C'];

    // ── Valeurs Likert min/max ────────────────────────────────────────────
    private const LIKERT_MIN = 1;
    private const LIKERT_MAX = 5;

    // ── TTL du cache questions (en secondes) ──────────────────────────────
    private const CACHE_QUESTIONS_TTL = 3600;

    // ── Libellés d'interprétation par dimension ───────────────────────────
    private const DIMENSION_PROFILES = [
        'R' => [
            'label'       => 'Réaliste',
            'emoji'       => '🔧',
            'description' => 'Tu as un profil pratique et concret. Tu aimes travailler avec tes mains, manipuler des outils et voir des résultats tangibles. Tu es fiable, persistant(e) et tu préfères l\'action à la théorie.',
            'forces'      => ['Dextérité manuelle', 'Sens pratique', 'Endurance physique', 'Fiabilité'],
            'metiers'     => ['Ingénieur civil', 'Technicien de maintenance', 'Architecte', 'Pilote', 'Chirurgien'],
            'filieres'    => ['Génie civil', 'Génie mécanique', 'Génie électrique', 'Agriculture'],
        ],
        'I' => [
            'label'       => 'Investigateur',
            'emoji'       => '🔬',
            'description' => 'Tu as un profil analytique et scientifique. Tu aimes comprendre le monde, analyser des données complexes et résoudre des problèmes intellectuels. Curieux(se) et rigoureux(se), tu te nourris de connaissances.',
            'forces'      => ['Pensée analytique', 'Curiosité intellectuelle', 'Rigueur scientifique', 'Autonomie'],
            'metiers'     => ['Chercheur', 'Médecin', 'Data Scientist', 'Ingénieur R&D', 'Pharmacien'],
            'filieres'    => ['Médecine', 'Informatique', 'Physique', 'Mathématiques', 'Biologie'],
        ],
        'A' => [
            'label'       => 'Artistique',
            'emoji'       => '🎨',
            'description' => 'Tu as un profil créatif et expressif. Tu vois le monde autrement, tu aimes inventer, innover et exprimer tes idées. Sensible à l\'esthétique, tu t\'épanouis dans des environnements qui valorisent l\'originalité.',
            'forces'      => ['Créativité', 'Sens esthétique', 'Expression personnelle', 'Originalité'],
            'metiers'     => ['Architecte', 'Graphiste', 'Journaliste', 'Designer UX', 'Réalisateur'],
            'filieres'    => ['Architecture', 'Design', 'Communication', 'Arts', 'Lettres'],
        ],
        'S' => [
            'label'       => 'Social',
            'emoji'       => '🤝',
            'description' => 'Tu as un profil humain et altruiste. Tu aimes aider, enseigner, conseiller et travailler avec les autres. Empathique et communicant(e), tu trouves ton sens dans les relations et l\'impact sur les personnes.',
            'forces'      => ['Empathie', 'Communication', 'Travail en équipe', 'Sens du service'],
            'metiers'     => ['Médecin', 'Enseignant', 'Psychologue', 'Assistante sociale', 'RH'],
            'filieres'    => ['Sciences de l\'éducation', 'Psychologie', 'Médecine', 'Droit', 'Sociologie'],
        ],
        'E' => [
            'label'       => 'Entreprenant',
            'emoji'       => '🚀',
            'description' => 'Tu as un profil leader et ambitieux. Tu aimes convaincre, diriger, prendre des risques calculés et influencer les décisions. Dynamique et charismatique, tu te projettes naturellement dans des rôles de responsabilité.',
            'forces'      => ['Leadership', 'Persuasion', 'Prise de décision', 'Ambition'],
            'metiers'     => ['Chef de projet', 'Entrepreneur', 'Avocat', 'Commercial', 'Directeur'],
            'filieres'    => ['Management', 'Droit', 'Commerce', 'Marketing', 'Économie'],
        ],
        'C' => [
            'label'       => 'Conventionnel',
            'emoji'       => '📋',
            'description' => 'Tu as un profil organisé et méthodique. Tu aimes la structure, les procédures claires et les environnements ordonnés. Précis(e), fiable et efficace, tu excelles dans la gestion de données et l\'organisation.',
            'forces'      => ['Organisation', 'Précision', 'Rigueur', 'Fiabilité'],
            'metiers'     => ['Comptable', 'Contrôleur de gestion', 'Notaire', 'Statisticien', 'Auditeur'],
            'filieres'    => ['Comptabilité', 'Finance', 'Informatique de gestion', 'Administration', 'Droit'],
        ],
    ];

    // ══════════════════════════════════════════════════════════════════════
    // 1. CHARGEMENT DES QUESTIONS
    // ══════════════════════════════════════════════════════════════════════

    public function getQuestions(): Collection
    {
        return Cache::remember('riasec.questions.grouped', self::CACHE_QUESTIONS_TTL, function () {
            return QuestionRiasec::actives()->ordonnes()->get()->groupBy('categorie');
        });
    }

    public function getAllQuestions(): Collection
    {
        return Cache::remember('riasec.questions.flat', self::CACHE_QUESTIONS_TTL, function () {
            return QuestionRiasec::actives()->ordonnes()->get();
        });
    }

    public function getNextQuestion(?int $userId, string $sessionId): ?QuestionRiasec
    {
        $answeredIds = $this->getAnsweredQuestionIds($userId, $sessionId);
        return QuestionRiasec::actives()->ordonnes()->whereNotIn('id', $answeredIds)->first();
    }

    // ══════════════════════════════════════════════════════════════════════
    // 2. ENREGISTREMENT DES RÉPONSES
    // ══════════════════════════════════════════════════════════════════════

    public function saveAnswer(
        ?int    $userId,
        int     $questionId,
        int     $score,
        string  $sessionId,
        ?string $guestId = null,
        ?int    $tempsMs = null
    ): AnswerRiasec {
        $question = QuestionRiasec::findOrFail($questionId);

        if (! $question->valeurEstValide($score)) {
            throw new \InvalidArgumentException(
                "Score {$score} invalide pour la question #{$questionId} (type: {$question->type_reponse})."
            );
        }

        Cache::forget("riasec.scores.{$sessionId}");

        return AnswerRiasec::enregistrer(
            sessionId:  $sessionId,
            questionId: $questionId,
            valeur:     $score,
            userId:     $userId,
            guestId:    $guestId,
            tempsMs:    $tempsMs
        );
    }

    // ══════════════════════════════════════════════════════════════════════
    // 3. CALCUL DES SCORES RIASEC (Holland uniquement)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Calcule les scores RIASEC bruts et normalisés.
     * ⚠️ Seules les 6 dimensions Holland (R/I/A/S/E/C) entrent dans le trigramme.
     * Les blocs Big Five, GATB, Résilience, Attention sont exclus ici.
     */
    public function calculateScores(?int $userId, string $sessionId): RiasecScoreDTO
    {
        return Cache::remember("riasec.scores.{$sessionId}", 300, function () use ($sessionId) {
            $answers = AnswerRiasec::session($sessionId)->avecQuestion()->get();

            $rawScores  = array_fill_keys(self::RIASEC_DIMS, 0);
            $maxScores  = array_fill_keys(self::RIASEC_DIMS, 0);
            $countByDim = array_fill_keys(self::RIASEC_DIMS, 0);

            foreach ($answers as $answer) {
                $question = $answer->question;

                // On n'inclut QUE les questions RIASEC pures (bloc 'riasec' ou dimension dans R/I/A/S/E/C)
                if (! $question) {
                    continue;
                }

                $dim = $question->dimension;
                if (! in_array($dim, self::RIASEC_DIMS, true)) {
                    continue; // Big Five, GATB, Résilience, Attention → ignorés ici
                }

                if ($question->bloc && $question->bloc !== 'riasec') {
                    continue; // Exclure les blocs autres que riasec pour les dimensions R/I/A/S/E/C
                }

                $poids = $question->poids;

                $valeur = $question->is_reverse
                    ? $this->invertScore($answer->valeur, $question->type_reponse)
                    : $answer->valeur;

                $rawScores[$dim]  += $valeur * $poids;
                $maxScores[$dim]  += self::LIKERT_MAX * $poids;
                $countByDim[$dim] += 1;
            }

            // Normalisation 0–100
            $normalized = [];
            foreach (self::RIASEC_DIMS as $dim) {
                $normalized[$dim] = $maxScores[$dim] > 0
                    ? round(($rawScores[$dim] / $maxScores[$dim]) * 100, 2)
                    : 0.0;
            }

            $trigram   = $this->determineDominantTrigram($normalized);
            $coherence = $this->calculateCoherenceScore($answers);

            return new RiasecScoreDTO(
                rawScores:        $rawScores,
                normalizedScores: $normalized,
                trigram:          $trigram,
                totalAnswers:     $answers->count(),
                coherenceScore:   $coherence,
            );
        });
    }

    // ══════════════════════════════════════════════════════════════════════
    // 4. TRIGRAMME DOMINANT
    // ══════════════════════════════════════════════════════════════════════

    public function determineDominantTrigram(array $normalizedScores): string
    {
        // Ne prend QUE les 6 dimensions Holland
        $scores = array_intersect_key($normalizedScores, array_flip(self::RIASEC_DIMS));

        uksort($scores, function (string $a, string $b) use ($scores): int {
            $diff = $scores[$b] <=> $scores[$a];
            if ($diff !== 0) return $diff;
            return self::HOLLAND_PRIORITY[$a] <=> self::HOLLAND_PRIORITY[$b];
        });

        return implode('', array_slice(array_keys($scores), 0, 3));
    }

    // ══════════════════════════════════════════════════════════════════════
    // 5. COMPLÉTION DU TEST
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Vérifie si le test est terminé.
     * Accepte le flag is_completed du moteur CAT (arrêt adaptatif précoce)
     * OU vérifie que toutes les questions actives ont été répondues.
     *
     * @param bool $catCompleted  Flag direct du AdaptiveTestEngine (prioritaire)
     */
    public function isTestCompleted(?int $userId, string $sessionId, bool $catCompleted = false): bool
    {
        if ($catCompleted) {
            return true;
        }

        // Fallback : toutes les questions actives répondues
        $totalActive   = QuestionRiasec::actives()->count();
        $totalAnswered = AnswerRiasec::session($sessionId)->count();
        return $totalAnswered >= $totalActive;
    }

    // ══════════════════════════════════════════════════════════════════════
    // 6. PROGRESSION
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Retourne l'état de progression du test.
     *
     * Le "total" affiché à l'étudiant = questions RIASEC pures (30)
     * pour éviter d'afficher "18/74" qui panique les étudiants.
     * L'arrêt adaptatif est signalé via $catCompleted.
     *
     * @param bool $catCompleted  Flag direct du AdaptiveTestEngine
     */
    public function getProgress(?int $userId, string $sessionId, bool $catCompleted = false): TestProgressDTO
    {
        // Total affiché = questions RIASEC seulement (barre de progression lisible)
        $riasecQuestions = QuestionRiasec::actives()
            ->whereIn('dimension', self::RIASEC_DIMS)
            ->where('bloc', 'riasec')
            ->ordonnes()
            ->get();

        $total    = $riasecQuestions->count(); // 30
        $answered = AnswerRiasec::session($sessionId)->count();

        $allDimensions  = array_fill_keys(self::RIASEC_DIMS, 0);
        $answeredByDim  = $allDimensions;
        $remainingByDim = array_fill_keys(self::RIASEC_DIMS, 0);
        $lastQuestionId = null;

        $answeredIds = AnswerRiasec::session($sessionId)->pluck('question_id')->flip();

        foreach ($riasecQuestions as $q) {
            if ($answeredIds->has($q->id)) {
                $answeredByDim[$q->dimension]++;
                $lastQuestionId = $q->id;
            } else {
                $remainingByDim[$q->dimension]++;
            }
        }

        // La progression affichée est basée sur les questions RIASEC
        $riasecAnswered = array_sum($answeredByDim);
        $percentage     = $total > 0 ? ($riasecAnswered / $total) * 100 : 0.0;

        // Terminé si : arrêt adaptatif OU toutes les questions RIASEC répondues
        $isCompleted = $catCompleted || ($riasecAnswered >= $total);

        return new TestProgressDTO(
            answered:             $answered, // total réponses session (pour le log)
            total:                $total,    // 30 questions RIASEC (pour la barre)
            percentage:           $percentage,
            isCompleted:          $isCompleted,
            answeredByDimension:  $answeredByDim,
            remainingByDimension: $remainingByDim,
            lastQuestionId:       $lastQuestionId,
            sessionId:            $sessionId,
        );
    }

    // ══════════════════════════════════════════════════════════════════════
    // 7. GESTION DU PROFIL (avec GATB + Résilience)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Crée ou met à jour le ProfileRiasec avec les scores RIASEC, GATB et Résilience.
     */
    public function saveProfile(?int $userId, string $sessionId, ?string $guestId = null): ProfileRiasec
    {
        $scores   = $this->calculateScores($userId, $sessionId);
        $progress = $this->getProgress($userId, $sessionId);
        $interp   = $this->generateInterpretation($scores);

        // ── Calcul GATB (scores objectifs) ───────────────────────────────
        $gatbScores = $this->calculateGatbScores($sessionId);

        // ── Calcul Résilience ─────────────────────────────────────────────
        $resilienceScore = $this->calculateResilienceScore($sessionId);

        return ProfileRiasec::updateOrCreate(
            ['test_session_id' => $sessionId],
            [
                'user_id'                => $userId,
                'session_guest_id'       => $guestId,
                'score_r'                => (int) round($scores->scoreR()),
                'score_i'                => (int) round($scores->scoreI()),
                'score_a'                => (int) round($scores->scoreA()),
                'score_s'                => (int) round($scores->scoreS()),
                'score_e'                => (int) round($scores->scoreE()),
                'score_c'                => (int) round($scores->scoreC()),
                'code_holland'           => $scores->trigram,
                'statut'                 => $progress->isCompleted
                                              ? ProfileRiasec::STATUT_COMPLET
                                              : ProfileRiasec::STATUT_EN_COURS,
                'nb_questions_repondues' => $progress->answered,
                'nb_questions_total'     => $progress->total,
                'score_coherence'        => $scores->coherenceScore,
                'interpretation'         => $interp,
                'complete_at'            => $progress->isCompleted ? now() : null,

                // ── GATB Scores (0–100) ──────────────────────────────────
                'score_gatb_g'           => $gatbScores['GATB_G'] ?? 0,
                'score_gatb_v'           => $gatbScores['GATB_V'] ?? 0,
                'score_gatb_n'           => $gatbScores['GATB_N'] ?? 0,
                'score_gatb_s'           => $gatbScores['GATB_S'] ?? 0,

                // ── Résilience (0–100) ────────────────────────────────────
                'score_resilience'       => $resilienceScore,
            ]
        );
    }

    // ══════════════════════════════════════════════════════════════════════
    // 8. INTERPRÉTATION TEXTUELLE
    // ══════════════════════════════════════════════════════════════════════

    public function generateInterpretation(RiasecScoreDTO $scores): array
    {
        $trigram  = $scores->trigram;
        $dominant = $scores->dominantDimension();
        $top3     = str_split($trigram);

        $profiles = array_map(
            fn ($dim) => self::DIMENSION_PROFILES[$dim] ?? [],
            $top3
        );

        $allFilieres = collect($profiles)->flatMap(fn ($p) => $p['filieres'] ?? [])->unique()->values()->all();
        $allMetiers  = collect($profiles)->flatMap(fn ($p) => $p['metiers'] ?? [])->unique()->values()->all();
        $allForces   = collect($profiles)->flatMap(fn ($p) => $p['forces'] ?? [])->unique()->values()->all();

        $fiabiliteNote = match (true) {
            $scores->coherenceScore >= 80 => 'Ce profil présente un niveau de cohérence excellent. Les résultats sont très fiables.',
            $scores->coherenceScore >= 60 => 'Ce profil est globalement cohérent. Les résultats sont fiables.',
            $scores->coherenceScore >= 40 => 'Ce profil montre une cohérence modérée. Certaines réponses semblent contradictoires.',
            default                       => 'La cohérence de ce profil est faible. Il est conseillé de repasser le test avec plus d\'attention.',
        };

        return [
            'trigram'            => $trigram,
            'dominant'           => $dominant,
            'profil_label'       => self::DIMENSION_PROFILES[$dominant]['label'] ?? '',
            'profil_emoji'       => self::DIMENSION_PROFILES[$dominant]['emoji'] ?? '',
            'description'        => $this->buildProfileDescription($top3, $scores),
            'forces'             => array_slice($allForces, 0, 6),
            'metiers_suggeres'   => array_slice($allMetiers, 0, 8),
            'filieres_suggerees' => array_slice($allFilieres, 0, 8),
            'scores_detail'      => $scores->toArray(),
            'fiabilite'          => $fiabiliteNote,
            'coherence_score'    => $scores->coherenceScore,
            'generated_at'       => now()->toIso8601String(),
        ];
    }

    // ══════════════════════════════════════════════════════════════════════
    // MÉTHODES PUBLIQUES UTILITAIRES
    // ══════════════════════════════════════════════════════════════════════

    public function generateSessionId(): string
    {
        return (string) Str::uuid();
    }

    public function invalidateSessionCache(string $sessionId): void
    {
        Cache::forget("riasec.scores.{$sessionId}");
    }

    public function getDimensionProfile(string $dimension): ?array
    {
        return self::DIMENSION_PROFILES[strtoupper($dimension)] ?? null;
    }

    // ══════════════════════════════════════════════════════════════════════
    // MÉTHODES PRIVÉES — CALCULS SPÉCIALISÉS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Calcule les scores GATB objectifs (0–100).
     * La valeur 5 = bonne réponse (100%), toute autre valeur = mauvaise réponse (0%).
     */
    private function calculateGatbScores(string $sessionId): array
    {
        $scores = [
            'GATB_G' => 0,
            'GATB_V' => 0,
            'GATB_N' => 0,
            'GATB_S' => 0,
        ];

        $answers = AnswerRiasec::session($sessionId)
            ->avecQuestion()
            ->get()
            ->filter(fn ($a) => $a->question && $a->question->bloc === 'gatb');

        $totals  = ['GATB_G' => 0, 'GATB_V' => 0, 'GATB_N' => 0, 'GATB_S' => 0];
        $correct = ['GATB_G' => 0, 'GATB_V' => 0, 'GATB_N' => 0, 'GATB_S' => 0];

        foreach ($answers as $answer) {
            $dim = $answer->question->dimension;
            if (! isset($totals[$dim])) {
                continue;
            }
            $totals[$dim]++;
            if ($answer->valeur === 5) {
                $correct[$dim]++;
            }
        }

        foreach ($scores as $dim => $_) {
            $scores[$dim] = $totals[$dim] > 0
                ? (int) round(($correct[$dim] / $totals[$dim]) * 100)
                : 0;
        }

        return $scores;
    }

    /**
     * Calcule le score de résilience/persévérance (0–100).
     * Basé sur les questions Likert du bloc 'resilience' (avec inversion des items inversés).
     */
    private function calculateResilienceScore(string $sessionId): int
    {
        $answers = AnswerRiasec::session($sessionId)
            ->avecQuestion()
            ->get()
            ->filter(fn ($a) => $a->question && $a->question->bloc === 'resilience');

        if ($answers->isEmpty()) {
            return 0;
        }

        $totalRaw = 0;
        $maxRaw   = 0;

        foreach ($answers as $answer) {
            $valeur = $answer->question->is_reverse
                ? (6 - $answer->valeur)
                : $answer->valeur;

            $totalRaw += $valeur;
            $maxRaw   += self::LIKERT_MAX;
        }

        return $maxRaw > 0 ? (int) round(($totalRaw / $maxRaw) * 100) : 0;
    }

    /**
     * Calcule le score de cohérence interne (0–100) sur les seules dimensions RIASEC.
     * Exclut explicitement Big Five, GATB, Résilience et Attention.
     */
    private function calculateCoherenceScore(Collection $answers): int
    {
        // Filtre sur RIASEC pur (bloc 'riasec' ou dimension dans R/I/A/S/E/C sans autre bloc)
        $riasecAnswers = $answers->filter(function ($a) {
            $q = $a->question;
            return $q
                && in_array($q->dimension, self::RIASEC_DIMS, true)
                && (! $q->bloc || $q->bloc === 'riasec');
        });

        if ($riasecAnswers->isEmpty()) {
            return 0;
        }

        $varianceByDim = [];

        foreach (self::RIASEC_DIMS as $dim) {
            $dimAnswers = $riasecAnswers
                ->filter(fn ($a) => $a->question?->dimension === $dim)
                ->pluck('valeur');

            if ($dimAnswers->count() < 2) {
                continue;
            }

            $mean     = $dimAnswers->avg();
            $variance = $dimAnswers->map(fn ($v) => pow($v - $mean, 2))->avg();
            $varianceByDim[$dim] = $variance;
        }

        if (empty($varianceByDim)) {
            return 100;
        }

        $maxVariance = 4.0; // Variance max théorique sur échelle 1-5
        $avgVariance = array_sum($varianceByDim) / count($varianceByDim);
        $coherence   = max(0, 100 - (int) round(($avgVariance / $maxVariance) * 100));

        return min(100, $coherence);
    }

    /**
     * Inverse un score Likert (6 - valeur) ou boolean (1 - valeur).
     */
    private function invertScore(int $valeur, string $typeReponse): int
    {
        return match ($typeReponse) {
            'likert'  => (self::LIKERT_MIN + self::LIKERT_MAX) - $valeur,
            'boolean' => 1 - $valeur,
            default   => $valeur,
        };
    }

    private function getAnsweredQuestionIds(?int $userId, string $sessionId): array
    {
        return AnswerRiasec::session($sessionId)->pluck('question_id')->all();
    }

    private function buildProfileDescription(array $top3, RiasecScoreDTO $scores): string
    {
        $primary   = self::DIMENSION_PROFILES[$top3[0]] ?? null;
        $secondary = self::DIMENSION_PROFILES[$top3[1]] ?? null;

        if (! $primary) {
            return 'Profil RIASEC en cours d\'analyse.';
        }

        $desc = $primary['description'];

        if ($secondary) {
            $label2 = strtolower($secondary['label']);
            $score2 = round($scores->normalizedScores[$top3[1]] ?? 0);

            if ($score2 >= 60) {
                $desc .= " Tu as également un fort penchant {$label2}, ce qui enrichit ton profil avec des compétences complémentaires.";
            } elseif ($score2 >= 40) {
                $desc .= " Une tendance {$label2} secondaire vient nuancer ton profil.";
            }
        }

        return $desc;
    }
}
