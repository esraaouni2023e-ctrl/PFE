<?php

namespace App\Services\RIASEC;

use App\Models\AnswerRiasec;

class BehavioralAnalyzer
{
    private array $config;

    public function __construct()
    {
        $this->config = config('adaptive_test.behavioral');
    }

    /**
     * Analyse la réponse et met à jour les alertes de l'état de session.
     */
    public function evaluateBehavior(AnswerRiasec $answer, int $tempsMs, array &$state): void
    {
        if (!isset($state['alerts'])) {
            $state['alerts'] = [];
        }

        if (!isset($state['last_answers'])) {
            $state['last_answers'] = [];
        }

        if (!isset($state['last_times'])) {
            $state['last_times'] = [];
        }

        $valeur = $answer->valeur;
        $question = $answer->question;
        $dim = $question->dimension ?? 'R';

        // Historisation
        $state['last_answers'][] = $valeur;
        $state['last_times'][] = $tempsMs;
        
        $numAnswers = count($state['last_answers']);

        // 1. Vélocité (Temps moyen / Speedrunning)
        if ($numAnswers >= 10) {
            $avgTime = array_sum($state['last_times']) / $numAnswers;
            if ($avgTime < $this->config['speed_threshold_ms']) {
                $this->addAlert($state, 'impulsive_responses', "Temps moyen anormalement faible (< {$this->config['speed_threshold_ms']}ms).");
            }
        }

        // 2. Variance (Réponses plates)
        if ($numAnswers >= 15) {
            $recentAnswers = array_slice($state['last_answers'], -15);
            $variance = $this->calculateVariance($recentAnswers);
            if ($variance < $this->config['flat_variance_threshold']) {
                $this->addAlert($state, 'flat_responses', "Variance des réponses trop faible (réponses systématiques au milieu).");
            }
        }

        // 3. Incohérence temporelle (Saut de Theta) - Géré dans AdaptiveTestEngine via l'écart de certitude
        // On va vérifier si le saut prévu pour cette dimension est énorme.
        // Si la réponse actuelle dévie massivement de la moyenne des réponses précédentes sur la même dimension...
        // Pour simplifier ici, on tracke la dernière valeur donnée pour cette dimension
        if (!isset($state['last_val_by_dim'])) {
            $state['last_val_by_dim'] = [];
        }

        if (isset($state['last_val_by_dim'][$dim])) {
            $diff = abs($valeur - $state['last_val_by_dim'][$dim]);
            if ($diff >= 3) { // Ex: passer de 1 à 4 ou 5
                $this->addAlert($state, 'temporal_inconsistency', "Changement brusque d'avis sur la dimension {$dim}.");
            }
        }
        $state['last_val_by_dim'][$dim] = $valeur;

        // 4. Déviation Sociétale (Question avec beta très faible = tout le monde dit oui)
        // beta (difficulty) sera introduit en phase 3. On simule :
        $beta = $question->difficulty ?? 0.0;
        if ($beta < -1.5 && $valeur <= 2) {
            $this->addAlert($state, 'societal_deviation', "Réponse négative à une question à fort consensus social.");
        }

        // 5. Pattern Symétrique / Répétition (ex: 1, 1, 1, 1 ou 1, 2, 3, 2, 1)
        if ($numAnswers >= 5) {
            $last5 = array_slice($state['last_answers'], -5);
            if ($this->isSymmetric($last5) || count(array_unique($last5)) === 1) {
                $this->addAlert($state, 'symmetric_pattern', "Pattern de réponse mécanique ou symétrique détecté.");
            }
        }

        // Flag de l'état
        $alertCount = count($state['alerts']);
        if ($alertCount >= $this->config['max_alerts']) {
            $state['show_fraud_warning'] = true;
            $state['is_flagged'] = true;
        }
    }

    /**
     * Retourne le niveau de fiabilité global du profil
     */
    public function getProfileReliability(array $state): string
    {
        $count = count($state['alerts'] ?? []);
        if ($count <= 1) return 'high';
        if ($count == 2) return 'moderate';
        return 'low';
    }

    private function addAlert(array &$state, string $type, string $message): void
    {
        if (!isset($state['alerts'][$type])) {
            $state['alerts'][$type] = [
                'count' => 0,
                'message' => $message,
            ];
        }
        $state['alerts'][$type]['count']++;
    }

    private function calculateVariance(array $values): float
    {
        $count = count($values);
        if ($count === 0) return 0.0;
        $mean = array_sum($values) / $count;
        $carry = 0.0;
        foreach ($values as $val) {
            $carry += pow((float)$val - $mean, 2);
        }
        return $carry / $count;
    }

    private function isSymmetric(array $arr): bool
    {
        // Ex: 1,2,3,2,1
        if (count($arr) !== 5) return false;
        return $arr[0] == $arr[4] && $arr[1] == $arr[3] && $arr[0] != $arr[1];
    }
}
