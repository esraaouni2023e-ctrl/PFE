<?php

namespace App\Services\RIASEC;

use App\Models\AnswerRiasec;

/**
 * BehavioralAnalyzer v5.0 — Détection de fraude et d'inattention.
 *
 * Analyse les comportements de réponse en temps réel pour détecter :
 *  1. Vélocité excessive (speedrunning)
 *  2. Réponses plates ou systématiques (manque d'effort)
 *  3. Incohérence temporelle (changement brusque d'avis intra-dimension)
 *  4. Déviation sociétale (réponse négative à un item à fort consensus)
 *  5. Patterns mécaniques symétriques (1,2,3,2,1 etc.)
 *  6. Piège d'attention raté → flag immédiat (géré dans AdaptiveTestEngine)
 *  7. Réponse trop rapide pour une question cognitive GATB → alert
 */
class BehavioralAnalyzer
{
    private array $config;

    public function __construct()
    {
        $this->config = config('adaptive_test.behavioral', [
            'speed_threshold_ms'      => 3000,
            'flat_variance_threshold' => 0.5,
            'inconsistency_theta_jump'=> 1.5,
            'max_alerts'              => 3,
        ]);
    }

    /**
     * Analyse comportementale d'une réponse.
     * Ne traite PAS les blocs 'attention' et 'gatb' (gérés dans AdaptiveTestEngine).
     *
     * @param AnswerRiasec $answer   La réponse persistée
     * @param int          $tempsMs  Temps de réponse en millisecondes
     * @param array        $state    L'état CAT de la session (passé par référence)
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
        if (!isset($state['last_val_by_dim'])) {
            $state['last_val_by_dim'] = [];
        }

        $question = $answer->question;

        // Les pièges et questions GATB sont gérés dans AdaptiveTestEngine, on ne les re-traite pas
        if (!$question || in_array($question->bloc, ['attention', 'gatb'], true)) {
            return;
        }

        $valeur = $answer->valeur;
        $dim    = $question->dimension ?? 'R';

        // Historisation
        $state['last_answers'][] = $valeur;
        $state['last_times'][]   = $tempsMs;
        $numAnswers = count($state['last_answers']);

        // ── 1. Vélocité (speedrunning) ────────────────────────────────────
        if ($numAnswers >= 10) {
            $avgTime = array_sum($state['last_times']) / $numAnswers;
            if ($avgTime < $this->config['speed_threshold_ms']) {
                $this->addAlert(
                    $state,
                    'impulsive_responses',
                    "Temps moyen anormalement faible (< {$this->config['speed_threshold_ms']} ms)."
                );
            }
        }

        // ── 2. Réponses plates (variance trop faible) ─────────────────────
        if ($numAnswers >= 15) {
            $recentAnswers = array_slice($state['last_answers'], -15);
            $variance      = $this->calculateVariance($recentAnswers);
            if ($variance < $this->config['flat_variance_threshold']) {
                $this->addAlert(
                    $state,
                    'flat_responses',
                    'Variance des réponses trop faible (réponses systématiques ou aléatoires).'
                );
            }
        }

        // ── 3. Incohérence temporelle intra-dimension ─────────────────────
        if (isset($state['last_val_by_dim'][$dim])) {
            $diff = abs($valeur - $state['last_val_by_dim'][$dim]);
            if ($diff >= 3) {
                $this->addAlert(
                    $state,
                    'temporal_inconsistency',
                    "Changement brusque d'avis sur la dimension {$dim} (écart de {$diff} points)."
                );
            }
        }
        $state['last_val_by_dim'][$dim] = $valeur;

        // ── 4. Déviation sociétale ────────────────────────────────────────
        $beta = $question->difficulty ?? 0.0;
        if ($beta < -1.5 && $valeur <= 2) {
            $this->addAlert(
                $state,
                'societal_deviation',
                'Réponse négative à une question à fort consensus social.'
            );
        }

        // ── 5. Pattern mécanique / symétrique ────────────────────────────
        if ($numAnswers >= 5) {
            $last5 = array_slice($state['last_answers'], -5);
            if ($this->isSymmetric($last5) || count(array_unique($last5)) === 1) {
                $this->addAlert(
                    $state,
                    'symmetric_pattern',
                    'Pattern de réponse mécanique ou symétrique détecté (ex : 3,3,3,3 ou 1,2,3,2,1).'
                );
            }
        }

        // ── Seuil d'alerte global → flag de fiabilité ────────────────────
        $alertCount = count($state['alerts']);
        if ($alertCount >= $this->config['max_alerts']) {
            $state['show_fraud_warning'] = true;
            $state['is_flagged']         = true;
        }
    }

    /**
     * Retourne le niveau de fiabilité global du profil.
     *
     * @return string 'high' | 'moderate' | 'low'
     */
    public function getProfileReliability(array $state): string
    {
        $count = count($state['alerts'] ?? []);
        if ($count <= 1) return 'high';
        if ($count == 2) return 'moderate';
        return 'low';
    }

    // ── Helpers privés ────────────────────────────────────────────────────

    private function addAlert(array &$state, string $type, string $message): void
    {
        if (!isset($state['alerts'][$type])) {
            $state['alerts'][$type] = ['count' => 0, 'message' => $message];
        }
        $state['alerts'][$type]['count']++;
    }

    private function calculateVariance(array $values): float
    {
        $count = count($values);
        if ($count === 0) return 0.0;
        $mean  = array_sum($values) / $count;
        $carry = 0.0;
        foreach ($values as $val) {
            $carry += pow((float) $val - $mean, 2);
        }
        return $carry / $count;
    }

    private function isSymmetric(array $arr): bool
    {
        if (count($arr) !== 5) return false;
        return $arr[0] == $arr[4] && $arr[1] == $arr[3] && $arr[0] != $arr[1];
    }
}
