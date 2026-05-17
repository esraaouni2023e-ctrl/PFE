<?php

namespace App\Services\RIASEC;

use App\Models\ProfileRiasec;

/**
 * PostTestValidator v5.0 — Validation post-test comportementale et de fiabilité.
 *
 * Valide le profil en fin de test en intégrant :
 *  - Les alertes comportementales du BehavioralAnalyzer
 *  - Les résultats des pièges d'attention (Attention Checks)
 *  - Les flags de réponse rapide sur les exercices GATB
 *
 * Règles de validation :
 *  - 1 piège d'attention raté → pending_manual_review
 *  - 2+ pièges d'attention ratés → flagged avec raison explicitée
 *  - is_flagged = true → pending_manual_review
 *  - Aucun problème → auto_approved
 */
class PostTestValidator
{
    /**
     * Valide le profil à la fin du test et applique le marquage comportemental.
     *
     * @param ProfileRiasec $profile  Le profil généré
     * @param array         $catState L'état final du moteur adaptatif (alertes, flags, attention_raw)
     */
    public function validateProfile(ProfileRiasec $profile, array $catState): void
    {
        $isFlagged    = $catState['is_flagged'] ?? false;
        $alerts       = $catState['alerts'] ?? [];
        $attentionRaw = $catState['attention_raw'] ?? ['passed' => 0, 'failed' => 0, 'total' => 0];
        $attentionFailed = $attentionRaw['failed'] ?? 0;

        // Détection d'erreurs sur les pièges d'attention
        if ($attentionFailed > 0) {
            $isFlagged = true;
            if (! isset($alerts['failed_attention_check'])) {
                $alerts['failed_attention_check'] = [
                    'count'   => $attentionFailed,
                    'message' => "{$attentionFailed} piège(s) d'attention raté(s). Les réponses peuvent ne pas être fiables.",
                ];
            }
        }

        // Détection de réponses trop rapides sur GATB
        $rapidGatb = $alerts['rapid_cognitive_response']['count'] ?? 0;
        if ($rapidGatb >= 3) {
            $isFlagged = true;
        }

        if ($isFlagged || ! empty($alerts)) {
            $profile->is_flagged        = $isFlagged;
            $profile->validation_status = 'pending_manual_review';
            $profile->flag_reason       = json_encode($alerts);
        } else {
            $profile->is_flagged        = false;
            $profile->validation_status = 'auto_approved';
            $profile->flag_reason       = null;
        }

        $profile->save();
    }

    /**
     * Calcule le niveau de fiabilité global (pour affichage).
     *
     * @return string 'high' | 'moderate' | 'low' | 'unreliable'
     */
    public function getReliabilityLevel(array $catState): string
    {
        $attentionFailed = $catState['attention_raw']['failed'] ?? 0;
        $alertCount      = count($catState['alerts'] ?? []);

        if ($attentionFailed >= 2 || $alertCount >= 4) {
            return 'unreliable';
        }
        if ($attentionFailed === 1 || $alertCount >= 2) {
            return 'low';
        }
        if ($alertCount === 1) {
            return 'moderate';
        }
        return 'high';
    }
}
