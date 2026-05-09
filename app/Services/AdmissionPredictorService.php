<?php

namespace App\Services;

class AdmissionPredictorService
{
    /**
     * Calcule dynamiquement les chances d'admission pour une liste de formations.
     * Logique de simulation pour le MVP (à remplacer par un vrai modèle AI de classification si besoin).
     */
    public function predictAdmissionChances($profile, array $formations)
    {
        $predictions = [];
        
        foreach ($formations as $f) {
            $baseScore = 60;
            
            // Générer un score reproductible basé sur l'ID utilisateur et le nom de la formation
            $userId = $profile ? $profile->user_id : 0;
            $seed = crc32($f['name'] . $userId);
            mt_srand($seed);
            
            // Le score varie entre 45% et 98%
            $dynamicScore = min(98, max(45, $baseScore + mt_rand(-15, 35)));
            
            $predictions[] = [
                'icon'  => $f['icon'] ?? '🎓',
                'name'  => $f['name'],
                'univ'  => $f['univ'] ?? 'Université de Carthage',
                'score' => $dynamicScore,
            ];
        }
        
        // Trier par score décroissant
        usort($predictions, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return $predictions;
    }
}
