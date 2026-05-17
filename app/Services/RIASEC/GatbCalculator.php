<?php

namespace App\Services\RIASEC;

/**
 * GatbCalculator v5.0 — Calcul des aptitudes cognitives GATB.
 *
 * En v5.0, le GATB est évalué par des exercices objectifs (QCM) où :
 *  - valeur = 5 → réponse correcte
 *  - valeur ≠ 5 → réponse incorrecte
 *
 * Les dimensions utilisées dans la DB sont GATB_G, GATB_V, GATB_N, GATB_S.
 * La méthode supporte aussi les anciennes clés courtes G, V, N, S via un mapping.
 */
class GatbCalculator
{
    // ── Mapping anciens codes → nouveaux ─────────────────────────────────
    private const DIM_ALIAS = [
        'G'   => 'GATB_G',
        'V'   => 'GATB_V',
        'N'   => 'GATB_N',
        'Num' => 'GATB_N',
        'S'   => 'GATB_S',
        'Sp'  => 'GATB_S',
    ];

    /**
     * Calcule les scores GATB à partir des réponses brutes.
     *
     * Chaque réponse est sur une échelle 1-5 (mode exercice objectif).
     *  - valeur = 5 → bonne réponse → 100 points
     *  - valeur ≠ 5 → mauvaise réponse → 0 points
     *  - Résultat : moyenne en % par aptitude (0–100)
     *
     * @param array $gatbAnswers Chaque entrée doit avoir ['dimension' => string, 'score' => int]
     * @return array ['GATB_G' => float, 'GATB_V' => float, 'GATB_N' => float, 'GATB_S' => float, 'TOTAL' => float, 'PROFIL_DOMINANT' => string]
     */
    public function calculateScores(array $gatbAnswers): array
    {
        $correct = ['GATB_G' => 0, 'GATB_V' => 0, 'GATB_N' => 0, 'GATB_S' => 0];
        $totals  = ['GATB_G' => 0, 'GATB_V' => 0, 'GATB_N' => 0, 'GATB_S' => 0];

        foreach ($gatbAnswers as $answer) {
            $rawDim = $answer['dimension'] ?? 'GATB_G';
            $dim    = self::DIM_ALIAS[$rawDim] ?? $rawDim;
            $score  = (int) ($answer['score'] ?? 0);

            if (! isset($totals[$dim])) {
                continue;
            }

            $totals[$dim]++;
            // Mode objectif : 5 = correct (20 pts sur 20), tout autre = 0
            if ($score === 5) {
                $correct[$dim]++;
            }
        }

        $scores = [];
        foreach (['GATB_G', 'GATB_V', 'GATB_N', 'GATB_S'] as $dim) {
            $scores[$dim] = $totals[$dim] > 0
                ? round(($correct[$dim] / $totals[$dim]) * 100, 1)
                : 0.0;
        }

        // Score total : moyenne des 4 aptitudes
        $scores['TOTAL'] = round(array_sum(array_values($scores)) / 4, 1);

        // Profil dominant
        $subScores = array_filter($scores, fn ($k) => $k !== 'TOTAL', ARRAY_FILTER_USE_KEY);
        arsort($subScores);
        $scores['PROFIL_DOMINANT'] = array_key_first($subScores) ?? 'GATB_G';

        return $scores;
    }

    /**
     * Évalue la compatibilité entre les aptitudes de l'étudiant et les exigences d'une filière.
     *
     * @param array $studentScores  Scores GATB (0–100) de l'étudiant
     * @param array $filiereRequirements Exigences minimales de la filière (0–100)
     * @return array Rapport de compatibilité détaillé
     */
    public function evaluateCompatibility(array $studentScores, array $filiereRequirements): array
    {
        $compatibility = [];
        $totalGap      = 0;
        $aptitudes     = ['GATB_G', 'GATB_V', 'GATB_N', 'GATB_S'];

        // Supporte aussi les anciennes clés courtes dans les requirements
        foreach ($aptitudes as $dim) {
            $student  = (float) ($studentScores[$dim] ?? $studentScores[str_replace('GATB_', '', $dim)] ?? 50.0);
            $required = (float) ($filiereRequirements[$dim] ?? $filiereRequirements[str_replace('GATB_', '', $dim)] ?? 50.0);

            if ($student >= $required) {
                $compatibility[$dim] = '✅ OK';
                $gap = 0;
            } else {
                $gap = $required - $student;
                $compatibility[$dim] = "⚠️ Manque {$gap} pts";
                $totalGap += $gap;
            }
        }

        // Niveau global de compatibilité
        [$level, $score] = match (true) {
            $totalGap == 0    => ['EXCELLENT', 1.0],
            $totalGap <= 10   => ['BON (petit effort requis)', 0.8],
            $totalGap <= 25   => ['MOYEN (travail à prévoir)', 0.6],
            $totalGap <= 40   => ['FRAGILE (risque d\'échec)', 0.4],
            default           => ['INSUFFISANT (filière déconseillée)', 0.2],
        };

        return [
            'compatibility' => $compatibility,
            'total_gap'     => $totalGap,
            'level'         => $level,
            'score'         => $score,
        ];
    }
}
