<?php

namespace App\Services\RIASEC;

class IrtCalibrator
{
    /**
     * Calcule la probabilité attendue (0.0 à 1.0) selon le modèle de Rasch (2PL/3PL).
     *
     * @param float $theta Le niveau latent de l'étudiant (-3.0 à +3.0)
     * @param float $beta  La difficulté de la question (-2.0 à +2.0)
     * @param float $alpha La discrimination de la question (0.5 à 2.5)
     * @param float $gamma Le facteur de guessing (0.0 pour Likert)
     * @return float Probabilité attendue
     */
    public function calculateExpectedProbability(float $theta, float $beta, float $alpha, float $gamma = 0.0): float
    {
        // P(x=1) = gamma + (1 - gamma) * [ exp(a*(theta - b)) / (1 + exp(a*(theta - b))) ]
        $exponent = $alpha * ($theta - $beta);
        
        // Protection contre l'overflow
        if ($exponent > 20) {
            $logistic = 1.0;
        } elseif ($exponent < -20) {
            $logistic = 0.0;
        } else {
            $logistic = exp($exponent) / (1.0 + exp($exponent));
        }

        return $gamma + (1.0 - $gamma) * $logistic;
    }

    /**
     * Calcule la fonction d'Information de l'Item (Test Information Function).
     * Plus l'information est haute, plus l'erreur standard (SEM) baisse.
     *
     * @return float Information apportée par la question
     */
    public function calculateItemInformation(float $theta, float $beta, float $alpha, float $gamma = 0.0): float
    {
        $p = $this->calculateExpectedProbability($theta, $beta, $alpha, $gamma);
        
        if ($gamma > 0.0) {
            // Formule complète 3PL pour l'information
            $q = 1.0 - $p;
            return $alpha * $alpha * ($q / $p) * pow(($p - $gamma) / (1.0 - $gamma), 2);
        }

        // Modèle 2PL simplifié
        return $alpha * $alpha * $p * (1.0 - $p);
    }

    /**
     * Convertit une discrimination de la base (ex: 6.0 à 9.5) en discrimination IRT standard (0.5 à 2.5)
     */
    public function normalizeAlpha(float $dbDiscrimination): float
    {
        // 10.0 -> 2.5
        // 5.0 -> 1.25
        return max(0.1, $dbDiscrimination / 4.0);
    }

    /**
     * Met à jour le Theta de l'étudiant en utilisant une méthode d'estimation adaptative (MLE simplifiée).
     */
    public function estimateNewTheta(float $currentTheta, float $observedProb, float $expectedProb, float $itemInformation): float
    {
        // L'ajustement est proportionnel à l'erreur (observed - expected)
        // et inversement proportionnel à l'information (pour stabiliser si on a beaucoup d'infos)
        // En IRT classique Newton-Raphson : theta_new = theta_old + (Observed - Expected) / TestInformation
        // Pour éviter des sauts sauvages au début (quand TestInfo est faible), on borne le changement.
        
        $information = max(0.1, $itemInformation); // Éviter division par 0
        $adjustment = ($observedProb - $expectedProb) / $information;
        
        // Borner l'ajustement max par étape à 1.0 sur l'échelle Theta
        $adjustment = max(-1.0, min(1.0, $adjustment));

        $newTheta = $currentTheta + $adjustment;
        
        // Borner le Theta total entre -3.0 et +3.0
        return max(-3.0, min(3.0, $newTheta));
    }
}
