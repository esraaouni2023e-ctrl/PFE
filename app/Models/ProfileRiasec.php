<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle ProfileRiasec — Profil Holland calculé après complétion du test RIASEC.
 *
 * Un profil est généré automatiquement par RiasecScoringService à la fin
 * d'une session de test. Il centralise tous les scores et le code Holland.
 *
 * @property int         $id
 * @property string      $test_session_id     UUID unique par test
 * @property int|null    $user_id
 * @property string|null $session_guest_id
 * @property int         $score_r             Score dimension Réaliste (0-100)
 * @property int         $score_i
 * @property int         $score_a
 * @property int         $score_s
 * @property int         $score_e
 * @property int         $score_c
 * @property string      $code_holland        Ex: "IAS"
 * @property string      $statut              en_cours | complet | expire
 * @property int         $nb_questions_repondues
 * @property int         $nb_questions_total
 * @property int|null    $score_coherence     Fiabilité 0-100
 * @property array|null  $interpretation      JSON enrichi
 * @property int|null    $duree_minutes
 * @property \Carbon\Carbon|null $complete_at
 *
 * @property-read array  $scores_par_dimension  Tableau dimension => score
 * @property-read float  $progression_pct       Pourcentage de complétion
 * @property-read bool   $est_complet
 * @property-read string $dimension_dominante   Lettre de la dimension la plus forte
 */
class ProfileRiasec extends Model
{
    protected $table = 'riasec_profiles';

    const STATUT_EN_COURS = 'en_cours';
    const STATUT_COMPLET  = 'complet';
    const STATUT_EXPIRE   = 'expire';

    protected $fillable = [
        'test_session_id',
        'user_id',
        'session_guest_id',
        'score_r', 'score_i', 'score_a', 'score_s', 'score_e', 'score_c',
        'code_holland',
        'statut',
        'nb_questions_repondues',
        'nb_questions_total',
        'score_coherence',
        'interpretation',
        'duree_minutes',
        'complete_at',
    ];

    protected $casts = [
        'score_r'                 => 'integer',
        'score_i'                 => 'integer',
        'score_a'                 => 'integer',
        'score_s'                 => 'integer',
        'score_e'                 => 'integer',
        'score_c'                 => 'integer',
        'score_coherence'         => 'integer',
        'nb_questions_repondues'  => 'integer',
        'nb_questions_total'      => 'integer',
        'duree_minutes'           => 'integer',
        'interpretation'          => 'array',
        'complete_at'             => 'datetime',
    ];

    protected $hidden = [];

    // ══════════════════════════════════════════════════════════════════════
    // RELATIONS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * L'utilisateur authentifié ayant passé ce test (null si invité).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Toutes les réponses liées à cette session de test.
     */
    public function reponses(): HasMany
    {
        return $this->hasMany(AnswerRiasec::class, 'test_session_id', 'test_session_id');
    }

    // ══════════════════════════════════════════════════════════════════════
    // SCOPES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Uniquement les profils complétés.
     */
    public function scopeComplets(Builder $query): Builder
    {
        return $query->where('statut', self::STATUT_COMPLET);
    }

    /**
     * Profils d'un utilisateur authentifié.
     */
    public function scopePourUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filtre par code Holland (correspondance partielle ou exacte).
     * Ex: scopeHolland('I') → tous les codes contenant 'I' en 1ère position.
     */
    public function scopeHolland(Builder $query, string $code): Builder
    {
        return strlen($code) === 3
            ? $query->where('code_holland', $code)
            : $query->where('code_holland', 'like', "{$code}%");
    }

    /**
     * Profils avec un score de cohérence minimum (fiabilité).
     */
    public function scopeCoherenceMin(Builder $query, int $min): Builder
    {
        return $query->where('score_coherence', '>=', $min);
    }

    /**
     * Tri du plus récent au plus ancien.
     */
    public function scopeRecents(Builder $query): Builder
    {
        return $query->orderByDesc('complete_at')->orderByDesc('created_at');
    }

    // ══════════════════════════════════════════════════════════════════════
    // ACCESSEURS CALCULÉS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Tableau associatif dimension → score.
     * Ex: ['R' => 45, 'I' => 78, 'A' => 62, 'S' => 55, 'E' => 40, 'C' => 30]
     *
     * @return array<string, int>
     */
    public function getScoresParDimensionAttribute(): array
    {
        return [
            'R' => $this->score_r,
            'I' => $this->score_i,
            'A' => $this->score_a,
            'S' => $this->score_s,
            'E' => $this->score_e,
            'C' => $this->score_c,
        ];
    }

    /**
     * Lettre de la dimension dominante (score le plus élevé).
     */
    public function getDimensionDominanteAttribute(): string
    {
        $scores = $this->scores_par_dimension;
        arsort($scores);
        return array_key_first($scores);
    }

    /**
     * Pourcentage de progression du test (0-100).
     */
    public function getProgressionPctAttribute(): float
    {
        if ($this->nb_questions_total === 0) {
            return 0.0;
        }
        return round(($this->nb_questions_repondues / $this->nb_questions_total) * 100, 1);
    }

    /**
     * Indique si le test est entièrement complété.
     */
    public function getEstCompletAttribute(): bool
    {
        return $this->statut === self::STATUT_COMPLET;
    }

    /**
     * Libellé du code Holland avec noms complets.
     * Ex: "IAS" → "Investigateur · Artistique · Social"
     */
    public function getCodeHollandLibelleAttribute(): string
    {
        return collect(str_split($this->code_holland))
            ->map(fn ($l) => QuestionRiasec::DIMENSIONS[$l] ?? $l)
            ->implode(' · ');
    }

    /**
     * Score de cohérence interprété en niveau qualitatif.
     */
    public function getNiveauCoherenceAttribute(): string
    {
        return match (true) {
            $this->score_coherence >= 80 => 'Excellent',
            $this->score_coherence >= 60 => 'Satisfaisant',
            $this->score_coherence >= 40 => 'Modéré',
            default                      => 'Insuffisant',
        };
    }

    // ══════════════════════════════════════════════════════════════════════
    // MÉTHODES MÉTIER
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Marque le profil comme complété et enregistre l'horodatage.
     */
    public function marquerComplet(): void
    {
        $this->update([
            'statut'      => self::STATUT_COMPLET,
            'complete_at' => now(),
        ]);
    }

    /**
     * Recalcule et met à jour le code Holland à partir des scores actuels.
     * Doit être appelé après chaque mise à jour des scores.
     */
    public function recalculerCodeHolland(): void
    {
        $scores = $this->scores_par_dimension;
        arsort($scores);
        $top3 = implode('', array_slice(array_keys($scores), 0, 3));

        $this->update(['code_holland' => $top3]);
    }

    /**
     * Retourne les filières compatibles extraites de l'interprétation JSON.
     *
     * @return array<string>
     */
    public function getFilieresCompatibles(): array
    {
        return $this->interpretation['filieres_suggerees'] ?? [];
    }

    /**
     * Vérifie si ce profil appartient à un utilisateur ou invité donné.
     */
    public function appartientA(?int $userId, ?string $guestId): bool
    {
        if ($userId !== null) {
            return $this->user_id === $userId;
        }
        return $this->session_guest_id === $guestId;
    }
}
