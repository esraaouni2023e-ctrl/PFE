<?php

namespace App\Services\RIASEC;

class GatbCalculator
{
    /**
     * Calcule les scores GATB à partir des réponses
     * Chaque réponse est sur une échelle de 1 à 5
     */
    public function calculateScores(array $gatbAnswers): array
    {
        $scores = [
            'G' => 0,  // Général (intelligence générale)
            'V' => 0,  // Verbal
            'N' => 0,  // Numérique
            'S' => 0,  // Spatial
        ];
        
        $counts = ['G' => 0, 'V' => 0, 'N' => 0, 'S' => 0];
        
        // Table de correspondance Likert vers note sur 20
        $mapping = [1 => 0, 2 => 5, 3 => 10, 4 => 15, 5 => 20];
        
        foreach ($gatbAnswers as $answer) {
            $aptitude = $answer['dimension'] ?? 'G';
            $responseValue = (int) $answer['score'];
            
            $valeurSur20 = $mapping[$responseValue] ?? 10;
            
            if (isset($scores[$aptitude])) {
                $scores[$aptitude] += $valeurSur20;
                $counts[$aptitude]++;
            }
        }
        
        // Moyenne par aptitude pour obtenir la note finale sur 20
        foreach ($scores as $key => $total) {
            $nb = $counts[$key];
            $scores[$key] = $nb > 0 ? round($total / $nb, 1) : 10.0;
        }
        
        // Score GATB total (moyenne des 4 aptitudes)
        $scores['TOTAL'] = round(array_sum($scores) / 4, 1);
        
        // Détermination du profil d'aptitudes dominant
        $maxScore = max($scores);
        $dominants = array_keys(array_filter($scores, fn($v) => $v === $maxScore && $v !== 'TOTAL'));
        $scores['PROFIL_DOMINANT'] = !empty($dominants) ? $dominants[0] : 'G';
        
        return $scores;
    }
    
    /**
     * Évalue la compatibilité entre les aptitudes de l'étudiant et une filière
     */
    public function evaluateCompatibility(array $studentScores, array $filiereRequirements): array
    {
        $compatibility = [];
        $totalGap = 0;
        
        $aptitudes = ['G', 'V', 'N', 'S'];
        
        foreach ($aptitudes as $apt) {
            $student = $studentScores[$apt] ?? 10;
            $required = $filiereRequirements[$apt] ?? 10; // Seuil par défaut
            
            if ($student >= $required) {
                $compatibility[$apt] = '✅ OK';
                $gap = 0;
            } else {
                $gap = $required - $student;
                $compatibility[$apt] = "⚠️ Manque {$gap} points";
                $totalGap += $gap;
            }
        }
        
        // Niveau global
        if ($totalGap == 0) {
            $level = 'EXCELLENT';
            $score = 1.0;
        } elseif ($totalGap <= 5) {
            $level = 'BON (petit effort requis)';
            $score = 0.8;
        } elseif ($totalGap <= 10) {
            $level = 'MOYEN (travail à prévoir)';
            $score = 0.6;
        } elseif ($totalGap <= 15) {
            $level = 'FRAGILE (risque d\'échec)';
            $score = 0.4;
        } else {
            $level = 'INSUFFISANT (filière déconseillée)';
            $score = 0.2;
        }
        
        return [
            'compatibility' => $compatibility,
            'total_gap' => $totalGap,
            'level' => $level,
            'score' => $score
        ];
    }
}
