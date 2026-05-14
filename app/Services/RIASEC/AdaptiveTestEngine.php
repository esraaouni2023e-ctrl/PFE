<?php

namespace App\Services\RIASEC;

use App\Models\QuestionRiasec;
use App\Models\AnswerRiasec;
use Illuminate\Support\Facades\Cache;

class AdaptiveTestEngine
{
    private BehavioralAnalyzer $behavioralAnalyzer;
    private IrtCalibrator $irtCalibrator;

    public function __construct(BehavioralAnalyzer $behavioralAnalyzer = null, IrtCalibrator $irtCalibrator = null)
    {
        $this->behavioralAnalyzer = $behavioralAnalyzer ?? new BehavioralAnalyzer();
        $this->irtCalibrator = $irtCalibrator ?? new IrtCalibrator();
    }
    /**
     * Phase 1: Modèle Bayésien
     * Initialise ou récupère l'état de croyance pour la session.
     * Le score (theta) est initialisé à 0.0 (neutre) sur l'échelle IRT (-3 à +3).
     */
    public function getSessionState(string $sessionId): array
    {
        return Cache::remember("cat_state_{$sessionId}", config('adaptive_test.cache_ttl', 7200), function () {
            return [
                'dimensions' => [
                    'R' => ['score' => 0.0, 'certainty' => 0.0],
                    'I' => ['score' => 0.0, 'certainty' => 0.0],
                    'A' => ['score' => 0.0, 'certainty' => 0.0],
                    'S' => ['score' => 0.0, 'certainty' => 0.0],
                    'E' => ['score' => 0.0, 'certainty' => 0.0],
                    'C' => ['score' => 0.0, 'certainty' => 0.0],
                ],
                'answered_ids' => [],
                'phase' => 0,
                'is_completed' => false,
                'completed_reason' => null,
                'alerts' => [],
            ];
        });
    }

    public function saveSessionState(string $sessionId, array $state): void
    {
        Cache::put("cat_state_{$sessionId}", $state, config('adaptive_test.cache_ttl', 7200));
    }

    /**
     * Calcule l'incertitude et sélectionne la prochaine question.
     */
    public function getNextQuestion(string $sessionId, ?int $userId = null): ?QuestionRiasec
    {
        $state = $this->getSessionState($sessionId);
        $answeredIds = $state['answered_ids'];
        $numAnswered = count($answeredIds);

        if ($state['is_completed'] || $numAnswered >= config('adaptive_test.max_questions', 20)) {
            return null; // Test terminé
        }

        $riasecDims = array_keys($state['dimensions']);

        // Phase 0 : Amorçage Global (2 premières questions très discriminantes)
        if ($numAnswered < 2) {
            return QuestionRiasec::actives()
                ->whereIn('dimension', $riasecDims)
                ->whereNotIn('id', $answeredIds)
                ->orderByDesc('discrimination')
                ->first();
        }

        // Phase Adaptative
        // 1. Trouver la dimension avec la plus forte incertitude
        $maxUncertainty = -1.0;
        $targetDim = null;

        foreach ($state['dimensions'] as $dim => $data) {
            $uncertainty = 1.0 - ($data['certainty'] / 100.0);
            if ($uncertainty > $maxUncertainty) {
                $maxUncertainty = $uncertainty;
                $targetDim = $dim;
            }
        }

        if (!$targetDim) {
            return null;
        }

        // 2. Sélectionner la question la plus discriminante pour cette dimension
        $question = QuestionRiasec::actives()
            ->where('dimension', $targetDim)
            ->whereNotIn('id', $answeredIds)
            ->orderByDesc('discrimination')
            ->first();

        // Fallback (au cas où la dimension n'a plus de questions disponibles)
        if (!$question) {
            $question = QuestionRiasec::actives()
                ->whereIn('dimension', $riasecDims)
                ->whereNotIn('id', $answeredIds)
                ->orderByDesc('discrimination')
                ->first();
        }

        return $question;
    }

    /**
     * Traite une nouvelle réponse, applique le decay, et gère l'arrêt.
     */
    public function processAnswer(string $sessionId, AnswerRiasec $answer, int $tempsMs = 5000): array
    {
        $state = $this->getSessionState($sessionId);
        $question = $answer->question;

        if (!$question) {
            return $state;
        }

        // Si la question n'est pas RIASEC, on l'ajoute juste aux répondus pour ne pas boucler
        if (!isset($state['dimensions'][$question->dimension])) {
            $state['answered_ids'][] = $question->id;
            $this->saveSessionState($sessionId, $state);
            return $state;
        }

        // Phase 2 : Analyse Comportementale
        $this->behavioralAnalyzer->evaluateBehavior($answer, $tempsMs, $state);

        $dim = $question->dimension;
        $valeur = $answer->valeur; // 1 à 5
        
        if ($question->is_reverse && $question->type_reponse === 'likert') {
            $valeur = 6 - $valeur;
        }

        $numAnswered = count($state['answered_ids']) + 1;
        
        // Phase 3 : Paramètres IRT
        $alphaDb = $question->discrimination ?? 5.0;
        $alpha = $this->irtCalibrator->normalizeAlpha($alphaDb); // 0.5 à 2.5
        $beta = $question->difficulty ?? 0.0; // -2.0 à +2.0
        $gamma = 0.0; // Pas de guessing pour Likert

        $currentTheta = $state['dimensions'][$dim]['score'];

        // Probabilité observée (transformée de Likert à 0.0-1.0)
        // 1 = 0.0, 3 = 0.5, 5 = 1.0
        $observedProb = ($valeur - 1) / 4.0;

        // Probabilité attendue selon Rasch
        $expectedProb = $this->irtCalibrator->calculateExpectedProbability($currentTheta, $beta, $alpha, $gamma);

        // Information apportée par cet item (pour la certitude)
        $itemInformation = $this->irtCalibrator->calculateItemInformation($currentTheta, $beta, $alpha, $gamma);

        // Mise à jour du Theta via MLE itérative
        $newTheta = $this->irtCalibrator->estimateNewTheta($currentTheta, $observedProb, $expectedProb, $itemInformation);
        $state['dimensions'][$dim]['score'] = $newTheta;

        // Mise à jour de la Certitude (L'information IRT se cumule)
        // On lisse l'augmentation (max ~8-10% par question)
        $certaintyIncrease = $itemInformation * 8.0; 
        $state['dimensions'][$dim]['certainty'] = min(100.0, $state['dimensions'][$dim]['certainty'] + $certaintyIncrease);
        
        $state['answered_ids'][] = $question->id;

        // Mise à jour de la Phase pour affichage
        if ($numAnswered < 2) {
            $state['phase'] = 0;
        } elseif ($numAnswered <= 6) {
            $state['phase'] = 1;
        } else {
            $state['phase'] = 2;
        }

        // Application des pénalités si fraud_warning est activé ou profil flagged
        if (isset($state['is_flagged']) && $state['is_flagged']) {
            // Empêcher la certitude de monter si le comportement est frauduleux
            $state['dimensions'][$dim]['certainty'] = max(0.0, $state['dimensions'][$dim]['certainty'] - 10.0);
        }

        // Phase 1 : Règle d'arrêt
        $dimensionsCertaines = 0;
        foreach ($state['dimensions'] as $d) {
            if ($d['certainty'] >= 80.0) {
                $dimensionsCertaines++;
            }
        }

        // On impose un minimum de 15 questions avant tout arrêt précoce pour garantir une fiabilité statistique
        $minQuestions = config('adaptive_test.stopping_rules.min_questions_before_stop', 15);
        if ($numAnswered >= $minQuestions && $dimensionsCertaines >= config('adaptive_test.stopping_rules.min_dimensions_reached', 3)) {
            $state['is_completed'] = true;
            $state['completed_reason'] = "certitude_atteinte";
        } elseif ($numAnswered >= config('adaptive_test.max_questions', 20)) {
            $state['is_completed'] = true;
            $state['completed_reason'] = "max_questions";
        }

        $this->saveSessionState($sessionId, $state);

        return $state;
    }

    public function invalidateSession(string $sessionId): void
    {
        Cache::forget("cat_state_{$sessionId}");
    }
}
