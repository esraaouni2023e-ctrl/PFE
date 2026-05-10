<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle SimulationHistory
 * Historique de toutes les simulations What-If effectuées par un étudiant.
 */
class SimulationHistory extends Model
{
    protected $table = 'simulation_history';

    protected $fillable = [
        'user_id',
        'section_bac',
        'moyenne_generale',
        'notes_matieres',
        'score_fg',
        'formations_accessibles',
        'label',
    ];

    protected $casts = [
        'notes_matieres'         => 'array',
        'formations_accessibles' => 'array',
        'moyenne_generale'       => 'float',
        'score_fg'               => 'float',
    ];

    // ── Relations ──────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ──────────────────────────────────

    /**
     * Retourne le label ou un label automatique basé sur la date.
     */
    public function getLabelOuDateAttribute(): string
    {
        return $this->label ?? 'Simulation du ' . $this->created_at->format('d/m/Y à H:i');
    }

    /**
     * Niveau de score FG (bas/moyen/élevé).
     */
    public function getNiveauScoreAttribute(): string
    {
        if ($this->score_fg >= 160) return 'excellent';
        if ($this->score_fg >= 130) return 'bon';
        if ($this->score_fg >= 100) return 'moyen';
        return 'faible';
    }
}
