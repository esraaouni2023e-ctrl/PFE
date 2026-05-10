<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Profile — Profil académique et cognitif de l'étudiant.
 */
class Profile extends Model
{
    protected $fillable = [
        'user_id',
        // Cognitif / IA
        'skills', 'interests', 'strengths', 'ai_score', 'summary', 'total_xp', 'settings',
        // Académique BAC
        'section_bac', 'moyenne_generale', 'annee_bac', 'gouvernorat',
        'notes_matieres', 'score_fg', 'score_fg_updated_at',
        // Conseiller
        'counselor_observations', 'coaching_plan', 'status',
    ];

    protected $casts = [
        'notes_matieres'         => 'array',
        'score_fg'               => 'float',
        'moyenne_generale'       => 'float',
        'ai_score'               => 'float',
        'total_xp'               => 'integer',
        'score_fg_updated_at'    => 'datetime',
    ];

    // ── Relations ──────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ──────────────────────────────────

    /**
     * Retourne true si le profil académique est complet.
     */
    public function getIsAcademiqueCompletAttribute(): bool
    {
        return !empty($this->section_bac)
            && !empty($this->moyenne_generale)
            && !empty($this->notes_matieres);
    }

    /**
     * Retourne la progression du profil en pourcentage.
     */
    public function getProgressionAttribute(): int
    {
        $fields = ['section_bac', 'moyenne_generale', 'notes_matieres', 'gouvernorat',
                   'interests', 'skills', 'score_fg'];
        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        return (int) round(($filled / count($fields)) * 100);
    }

    /**
     * Catégorie du score FG.
     */
    public function getNiveauFgAttribute(): string
    {
        if (!$this->score_fg) return 'non calculé';
        if ($this->score_fg >= 160) return 'Excellent';
        if ($this->score_fg >= 130) return 'Bon';
        if ($this->score_fg >= 100) return 'Moyen';
        return 'Insuffisant';
    }

    /**
     * Couleur associée au niveau FG.
     */
    public function getCouleurFgAttribute(): string
    {
        if (!$this->score_fg) return 'var(--ink30)';
        if ($this->score_fg >= 160) return 'var(--accent3)';
        if ($this->score_fg >= 130) return 'var(--gold)';
        if ($this->score_fg >= 100) return 'var(--accent)';
        return '#ef4444';
    }
}
