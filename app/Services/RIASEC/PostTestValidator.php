<?php

namespace App\Services\RIASEC;

use App\Models\ProfileRiasec;

class PostTestValidator
{
    /**
     * Valide le profil à la fin du test et applique le marquage comportemental.
     *
     * @param ProfileRiasec $profile Le profil généré
     * @param array $catState L'état final du moteur adaptatif (alertes, flags)
     * @return void
     */
    public function validateProfile(ProfileRiasec $profile, array $catState): void
    {
        $isFlagged = $catState['is_flagged'] ?? false;
        
        // Logique métier future: Croisement avec données qualitatives (Ollama)
        $hasQualitativeMismatch = false;

        if ($isFlagged || $hasQualitativeMismatch) {
            $profile->is_flagged = true;
            $profile->validation_status = 'pending_manual_review';
            $profile->flag_reason = json_encode($catState['alerts'] ?? []);
        } else {
            $profile->is_flagged = false;
            $profile->validation_status = 'auto_approved';
            $profile->flag_reason = null;
        }

        $profile->save();
    }
}
