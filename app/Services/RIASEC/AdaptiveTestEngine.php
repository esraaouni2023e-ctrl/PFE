<?php

namespace App\Services\RIASEC;

use App\Models\QuestionRiasec;
use App\Models\AnswerRiasec;
use Illuminate\Support\Facades\Cache;

/**
 * AdaptiveTestEngine v5.2 — Moteur adaptatif bayésien.
 *
 * CHANGEMENTS v5.2 :
 *  - Règle d'arrêt basée sur le comptage RIASEC (pas l'IRT certitude, impossible à atteindre)
 *  - Sélection des questions en 3 phases : couverture RIASEC → GATB intercalé → adaptatif
 *  - Big Five préfixé B5_ pour éviter la collision avec RIASEC (C/E/A)
 *  - max_questions réduit à 50 (réaliste avec la banque de 74 questions)
 */
class AdaptiveTestEngine
{
    private BehavioralAnalyzer $behavioralAnalyzer;
    private IrtCalibrator $irtCalibrator;

    private const RIASEC_DIMS = ['R', 'I', 'A', 'S', 'E', 'C'];

    // Nombre minimum de réponses RIASEC par dimension avant arrêt
    private const MIN_RIASEC_PER_DIM = 2; // 2 × 6 = 12 questions RIASEC

    public function __construct(BehavioralAnalyzer $behavioralAnalyzer = null, IrtCalibrator $irtCalibrator = null)
    {
        $this->behavioralAnalyzer = $behavioralAnalyzer ?? new BehavioralAnalyzer();
        $this->irtCalibrator      = $irtCalibrator ?? new IrtCalibrator();
    }

    // ══════════════════════════════════════════════════════════════════════
    // 1. ÉTAT DE SESSION
    // ══════════════════════════════════════════════════════════════════════

    public function getSessionState(string $sessionId): array
    {
        return Cache::remember("cat_state_{$sessionId}", config('adaptive_test.cache_ttl', 7200), function () {
            return [
                'dimensions' => [
                    // ── RIASEC ───────────────────────────────────────────
                    'R'        => $this->emptyDim(),
                    'I'        => $this->emptyDim(),
                    'A'        => $this->emptyDim(),
                    'S'        => $this->emptyDim(),
                    'E'        => $this->emptyDim(),
                    'C'        => $this->emptyDim(),
                    // ── Big Five (B5_ pour éviter collision avec R/I/A/S/E/C) ─
                    'B5_O'     => $this->emptyDim(),
                    'B5_C'     => $this->emptyDim(),
                    'B5_E'     => $this->emptyDim(),
                    'B5_A'     => $this->emptyDim(),
                    'B5_N'     => $this->emptyDim(),
                    // ── GATB ─────────────────────────────────────────────
                    'GATB_G'   => $this->emptyDim(),
                    'GATB_V'   => $this->emptyDim(),
                    'GATB_N'   => $this->emptyDim(),
                    'GATB_S'   => $this->emptyDim(),
                    // ── Résilience ────────────────────────────────────────
                    'RESILIENCE' => $this->emptyDim(),
                    // ── Filières intra-domaine ────────────────────────────
                    'MED'   => $this->emptyDim(),
                    'ENG'   => $this->emptyDim(),
                    'INFO'  => $this->emptyDim(),
                    'DROIT' => $this->emptyDim(),
                    'ECO'   => $this->emptyDim(),
                    'EDU'   => $this->emptyDim(),
                    'ART'   => $this->emptyDim(),
                    'LTR'   => $this->emptyDim(),
                    'SOC'   => $this->emptyDim(),
                    'SPO'   => $this->emptyDim(),
                    'ARCHI' => $this->emptyDim(),
                ],
                'global_sem'         => 1.0,
                'answered_ids'       => [],
                'phase'              => 0,
                'is_completed'       => false,
                'completed_reason'   => null,
                'alerts'             => [],
                'is_flagged'         => false,
                'show_fraud_warning' => false,
                'gatb_raw'           => [
                    'GATB_G' => ['correct' => 0, 'total' => 0],
                    'GATB_V' => ['correct' => 0, 'total' => 0],
                    'GATB_N' => ['correct' => 0, 'total' => 0],
                    'GATB_S' => ['correct' => 0, 'total' => 0],
                ],
                'resilience_raw'     => ['score' => 0, 'count' => 0],
                'attention_raw'      => ['passed' => 0, 'failed' => 0, 'total' => 0],
            ];
        });
    }

    public function saveSessionState(string $sessionId, array $state): void
    {
        Cache::put("cat_state_{$sessionId}", $state, config('adaptive_test.cache_ttl', 7200));
    }

    public function invalidateSession(string $sessionId): void
    {
        Cache::forget("cat_state_{$sessionId}");
    }

    // ══════════════════════════════════════════════════════════════════════
    // 2. SÉLECTION DE LA PROCHAINE QUESTION (3 phases)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Retourne la clé d'état pour une question (préfixe B5_ pour Big Five).
     */
    public function getDimensionKey(QuestionRiasec $question): string
    {
        if ($question->bloc === 'big_five') {
            return 'B5_' . $question->dimension;
        }
        return $question->dimension;
    }

    /**
     * Sélectionne la prochaine question en 3 phases :
     *
     *  Phase 1 : Couverture obligatoire — chaque dim RIASEC ≥ 3 réponses
     *  Phase 2 : Intercaler les GATB tous les 6 réponses
     *  Phase 3 : Adaptatif pur (max incertitude toutes dims)
     */
    public function getNextQuestion(string $sessionId, ?int $userId = null): ?QuestionRiasec
    {
        $state       = $this->getSessionState($sessionId);
        $answeredIds = $state['answered_ids'];
        $numAnswered = count($answeredIds);

        if ($state['is_completed'] || $numAnswered >= config('adaptive_test.max_questions', 50)) {
            return null;
        }

        // ── Phase 1 : Couverture RIASEC prioritaire ────────────────────────
        if ($numAnswered < 30) {
            $riasecCounts = $this->countRiasecAnswersByDim($sessionId);
            $underCovered = [];
            foreach (self::RIASEC_DIMS as $dim) {
                if (($riasecCounts[$dim] ?? 0) < self::MIN_RIASEC_PER_DIM) {
                    $underCovered[$dim] = $riasecCounts[$dim] ?? 0;
                }
            }

            if (!empty($underCovered)) {
                asort($underCovered);
                $targetDim    = array_key_first($underCovered);
                $currentTheta = $state['dimensions'][$targetDim]['score'] ?? 0.0;

                $available = QuestionRiasec::actives()
                    ->where('dimension', $targetDim)
                    ->where('bloc', 'riasec')
                    ->whereNotIn('id', $answeredIds)
                    ->get();

                $best = $this->selectBestQuestion($available, $currentTheta);
                if ($best) {
                    return $best;
                }
            }
        }

        // ── Phase 2 : GATB intercalé toutes les 6 questions ───────────────
        if ($numAnswered >= 6 && $numAnswered % 6 === 0) {
            $gatbQ = $this->pickGatbQuestion($answeredIds);
            if ($gatbQ) {
                return $gatbQ;
            }
        }

        // ── Phase 3 : Adaptatif complet ────────────────────────────────────
        $catDims = array_merge(
            self::RIASEC_DIMS,
            ['B5_O', 'B5_C', 'B5_E', 'B5_A', 'B5_N'],
            ['RESILIENCE'],
            ['MED', 'ENG', 'INFO', 'DROIT', 'ECO', 'EDU', 'ART', 'LTR', 'SOC', 'SPO', 'ARCHI']
        );

        $maxUncertainty = -1.0;
        $candidates     = [];

        foreach ($catDims as $dimKey) {
            if (!isset($state['dimensions'][$dimKey])) {
                continue;
            }
            $uncertainty = 1.0 - ($state['dimensions'][$dimKey]['certainty'] / 100.0);
            if ($uncertainty > $maxUncertainty) {
                $maxUncertainty = $uncertainty;
                $candidates     = [$dimKey];
            } elseif (abs($uncertainty - $maxUncertainty) < 0.001) {
                $candidates[] = $dimKey;
            }
        }

        $targetKey = !empty($candidates) ? $candidates[array_rand($candidates)] : null;

        if ($targetKey) {
            [$dbDimension, $dbBloc] = $this->keyToDbFields($targetKey);
            $currentTheta = $state['dimensions'][$targetKey]['score'] ?? 0.0;

            $available = QuestionRiasec::actives()
                ->where('dimension', $dbDimension)
                ->when($dbBloc, fn ($q) => $q->where('bloc', $dbBloc))
                ->whereNotIn('id', $answeredIds)
                ->get();

            $best = $this->selectBestQuestion($available, $currentTheta);
            if ($best) {
                return $best;
            }
        }

        return $this->fallbackQuestion($answeredIds, $state);
    }

    // ══════════════════════════════════════════════════════════════════════
    // 3. TRAITEMENT D'UNE RÉPONSE
    // ══════════════════════════════════════════════════════════════════════

    public function processAnswer(string $sessionId, AnswerRiasec $answer, int $tempsMs = 5000): array
    {
        $state    = $this->getSessionState($sessionId);
        $question = $answer->question;

        if (!$question) {
            return $state;
        }

        // ── Piège d'attention ────────────────────────────────────────────
        if ($question->dimension === 'ATTENTION' || $question->bloc === 'attention') {
            $state = $this->processAttentionCheck($answer, $state);
            $state['answered_ids'][] = $question->id;
            $this->saveSessionState($sessionId, $state);
            return $state;
        }

        // ── Exercice GATB objectif ────────────────────────────────────────
        if ($question->bloc === 'gatb') {
            $state = $this->processGatbAnswer($question, $answer, $state, $tempsMs);
            $state['answered_ids'][] = $question->id;
            // Applique les règles d'arrêt même après GATB
            $numAnswered = count($state['answered_ids']);
            $state = $this->applyStoppingRules($state, count($state['answered_ids']), $sessionId);
            $this->saveSessionState($sessionId, $state);
            return $state;
        }

        // ── Clé de dimension dans l'état ─────────────────────────────────
        $dimKey = $this->getDimensionKey($question);

        if (!isset($state['dimensions'][$dimKey])) {
            $state['answered_ids'][] = $question->id;
            $this->saveSessionState($sessionId, $state);
            return $state;
        }

        // ── Analyse comportementale ───────────────────────────────────────
        $this->behavioralAnalyzer->evaluateBehavior($answer, $tempsMs, $state);

        // ── Inversion Likert ──────────────────────────────────────────────
        $valeur = $answer->valeur;
        if ($question->is_reverse && $question->type_reponse === 'likert') {
            $valeur = 6 - $valeur;
        }

        $numAnswered = count($state['answered_ids']) + 1;

        // ── Mise à jour IRT bayésienne ────────────────────────────────────
        $alpha        = $this->irtCalibrator->normalizeAlpha($question->discrimination ?? 5.0);
        $beta         = $question->difficulty ?? 0.0;
        $currentTheta = $state['dimensions'][$dimKey]['score'];
        $observedProb = ($valeur - 1) / 4.0;
        $expectedProb = $this->irtCalibrator->calculateExpectedProbability($currentTheta, $beta, $alpha, 0.0);
        $itemInfo     = $this->irtCalibrator->calculateItemInformation($currentTheta, $beta, $alpha, 0.0);
        $newTheta     = $this->irtCalibrator->estimateNewTheta($currentTheta, $observedProb, $expectedProb, $itemInfo);

        $state['dimensions'][$dimKey]['score']     = $newTheta;
        $state['dimensions'][$dimKey]['total_info'] = ($state['dimensions'][$dimKey]['total_info'] ?? 0.0) + $itemInfo;

        $history   = $state['dimensions'][$dimKey]['theta_history'] ?? [];
        $history[] = $newTheta;
        if (count($history) > 5) {
            array_shift($history);
        }
        $state['dimensions'][$dimKey]['theta_history'] = $history;

        $sem = $this->irtCalibrator->calculateSem($state['dimensions'][$dimKey]['total_info']);
        $state['dimensions'][$dimKey]['certainty'] = max(0, min(100, (1.0 - $sem) * 100.0));

        if ($state['is_flagged']) {
            $state['dimensions'][$dimKey]['certainty'] = max(0.0, $state['dimensions'][$dimKey]['certainty'] - 10.0);
        }

        $state['answered_ids'][] = $question->id;

        $state['phase'] = match (true) {
            $numAnswered < 2  => 0,
            $numAnswered <= 6 => 1,
            default           => 2,
        };

        // ── Règles d'arrêt ────────────────────────────────────────────────
        $state = $this->applyStoppingRules($state, $numAnswered, $sessionId);

        $this->saveSessionState($sessionId, $state);
        return $state;
    }

    // ══════════════════════════════════════════════════════════════════════
    // RÈGLES D'ARRÊT v5.2 — COMPTAGE RIASEC DIRECT
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Arrêt basé sur le comptage direct des réponses RIASEC.
     *
     * Pourquoi pas l'IRT certitude ?
     *  Avec 5 questions/dim et alpha=1.25 → info max/dim = 1.95
     *  → certitude max = 28% — physiquement impossible d'atteindre 65%.
     *
     * Règle 1 : chaque dim RIASEC a ≥ 3 réponses (= 18 RIASEC total) → STOP
     * Règle 2 : total ≥ max_questions (50) → STOP de sécurité
     */
    private function applyStoppingRules(array $state, int $numAnswered, string $sessionId): array
    {
        $minQuestions = config('adaptive_test.min_questions', 20);
        $maxQuestions = config('adaptive_test.max_questions', 50);

        // Règle 1 : couverture RIASEC suffisante ET nombre minimum de questions atteint pour permettre l'adaptatif
        if ($numAnswered >= $minQuestions && $sessionId) {
            $counts     = $this->countRiasecAnswersByDim($sessionId);
            $allCovered = true;
            foreach (self::RIASEC_DIMS as $dim) {
                if (($counts[$dim] ?? 0) < self::MIN_RIASEC_PER_DIM) {
                    $allCovered = false;
                    break;
                }
            }
            if ($allCovered) {
                $state['is_completed']     = true;
                $state['completed_reason'] = 'couverture_riasec_complete';
                return $state;
            }
        }

        // Règle 2 : limite absolue de sécurité
        if ($numAnswered >= $maxQuestions) {
            $state['is_completed']     = true;
            $state['completed_reason'] = 'max_questions';
        }

        return $state;
    }

    // ══════════════════════════════════════════════════════════════════════
    // MÉTHODES PRIVÉES UTILITAIRES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Compte les réponses RIASEC par dimension pour la session en cours.
     * Requête DB légère : seulement les questions avec bloc='riasec'.
     */
    private function countRiasecAnswersByDim(string $sessionId): array
    {
        $counts = array_fill_keys(self::RIASEC_DIMS, 0);

        $rows = AnswerRiasec::where('test_session_id', $sessionId)
            ->whereHas('question', fn ($q) => $q->whereIn('dimension', self::RIASEC_DIMS)
                ->where('bloc', 'riasec'))
            ->with('question:id,dimension')
            ->get(['question_id']);

        foreach ($rows as $row) {
            $dim = $row->question?->dimension ?? null;
            if ($dim && isset($counts[$dim])) {
                $counts[$dim]++;
            }
        }

        return $counts;
    }

    private function processAttentionCheck(AnswerRiasec $answer, array $state): array
    {
        $state['attention_raw']['total']++;
        $isCorrect = $answer->valeur === 5;

        if ($isCorrect) {
            $state['attention_raw']['passed']++;
        } else {
            $state['attention_raw']['failed']++;
            $state['is_flagged']         = true;
            $state['show_fraud_warning'] = true;
            if (!isset($state['alerts']['failed_attention_check'])) {
                $state['alerts']['failed_attention_check'] = [
                    'count'   => 0,
                    'message' => "Piège d'attention raté : réponses potentiellement non-attentives.",
                ];
            }
            $state['alerts']['failed_attention_check']['count']++;
        }

        return $state;
    }

    private function processGatbAnswer(QuestionRiasec $question, AnswerRiasec $answer, array $state, int $tempsMs): array
    {
        // Réponse trop rapide pour une question cognitive
        if ($tempsMs < 4000) {
            if (!isset($state['alerts']['rapid_cognitive_response'])) {
                $state['alerts']['rapid_cognitive_response'] = [
                    'count'   => 0,
                    'message' => 'Réponse trop rapide pour une question cognitive GATB (< 4s).',
                ];
            }
            $state['alerts']['rapid_cognitive_response']['count']++;
            if ($state['alerts']['rapid_cognitive_response']['count'] >= 3) {
                $state['is_flagged']         = true;
                $state['show_fraud_warning'] = true;
            }
        }

        $dimKey    = $question->dimension; // GATB_G, GATB_V, GATB_N, GATB_S
        $isCorrect = $answer->valeur === 5;

        if (isset($state['gatb_raw'][$dimKey])) {
            $state['gatb_raw'][$dimKey]['total']++;
            if ($isCorrect) {
                $state['gatb_raw'][$dimKey]['correct']++;
            }
        }

        if (isset($state['dimensions'][$dimKey]) && ($state['gatb_raw'][$dimKey]['total'] ?? 0) > 0) {
            $accuracy = $state['gatb_raw'][$dimKey]['correct'] / $state['gatb_raw'][$dimKey]['total'];
            $state['dimensions'][$dimKey]['score']    = ($accuracy * 2.0) - 1.0;
            $state['dimensions'][$dimKey]['certainty'] = min(100, $state['gatb_raw'][$dimKey]['total'] * 33.3);
        }

        return $state;
    }

    private function keyToDbFields(string $key): array
    {
        if (str_starts_with($key, 'B5_')) {
            return [substr($key, 3), 'big_five'];
        }
        if ($key === 'RESILIENCE') {
            return ['RESILIENCE', 'resilience'];
        }
        $intraDims = ['MED', 'ENG', 'INFO', 'DROIT', 'ECO', 'EDU', 'ART', 'LTR', 'SOC', 'SPO', 'ARCHI', 'SANTE', 'ING', 'MGT'];
        if (in_array($key, $intraDims)) {
            return [$key, 'intra'];
        }
        return [$key, null];
    }

    private function pickGatbQuestion(array $answeredIds): ?QuestionRiasec
    {
        return QuestionRiasec::actives()
            ->where('bloc', 'gatb')
            ->whereNotIn('id', $answeredIds)
            ->inRandomOrder()
            ->first();
    }

    private function selectBestQuestion($questions, float $theta): ?QuestionRiasec
    {
        $best    = null;
        $maxInfo = -1.0;

        foreach ($questions as $q) {
            $alpha = $this->irtCalibrator->normalizeAlpha($q->discrimination ?? 5.0);
            $beta  = $q->difficulty ?? 0.0;
            $info  = $this->irtCalibrator->calculateItemInformation($theta, $beta, $alpha, 0.0);
            if ($info > $maxInfo) {
                $maxInfo = $info;
                $best    = $q;
            }
        }

        return $best;
    }

    private function fallbackQuestion(array $answeredIds, array $state): ?QuestionRiasec
    {
        return QuestionRiasec::actives()
            ->whereNotIn('id', $answeredIds)
            ->whereNotIn('dimension', ['ATTENTION'])
            ->inRandomOrder()
            ->first();
    }

    private function emptyDim(): array
    {
        return [
            'score'         => 0.0,
            'certainty'     => 0.0,
            'total_info'    => 0.0,
            'theta_history' => [],
        ];
    }
}
