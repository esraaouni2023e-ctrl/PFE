<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle OrientationVoeu
 * Représente un vœu d'orientation d'un étudiant pour une filière donnée.
 */
class OrientationVoeu extends Model
{
    protected $table = 'orientation_voeux';

    protected $fillable = [
        'user_id',
        'formation_id',
        'priorite',
        'notes_perso',
        'est_confirme',
    ];

    protected $casts = [
        'priorite'     => 'integer',
        'est_confirme' => 'boolean',
    ];

    // ── Relations ──────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    // ── Scopes ─────────────────────────────────────

    /**
     * Vœux ordonnés par priorité (1er vœu en premier).
     */
    public function scopeOrdonnes($query)
    {
        return $query->orderByRaw('CASE WHEN priorite = 0 THEN 9999 ELSE priorite END ASC')
                     ->orderBy('created_at', 'asc');
    }
}
