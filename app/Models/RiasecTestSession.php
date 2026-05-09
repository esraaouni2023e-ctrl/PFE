<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * RiasecTestSession — Modèle de session de test adaptatif.
 *
 * Représente une instance unique de test RIASEC pour un utilisateur ou un invité.
 * Contient tout l'état nécessaire au moteur adaptatif pour reprendre et continuer
 * un test à n'importe quel moment.
 *
 * @property int         $id
 * @property string      $session_token          UUID v4 unique
 * @property int|null    $user_id
 * @property string|null $session_guest_id
 * @property array|null  $demographic_data
 * @property array|null  $current_scores         {R:45.5, I:72.3, ...}
 * @property array       $administered_question_ids
 * @property array|null  $scores_history         [{step:4, R:40, ...}, ...]
 * @property int         $phase                  1=seed, 2=adaptive
 * @property bool        $seed_phase_complete
 * @property int         $total_questions_asked
 * @property int         $min_questions
 * @property int         $max_questions
 * @property float|null  $precision_score
 * @property float|null  $score_variance
 * @property float|null  $coherence_score
 * @property string|null $stop_reason
 * @property string      $statut                 en_cours|complet|abandon|expire
 * @property \Carbon\Carbon $started_at
 * @property \Carbon\Carbon|null $completed_at
 */
class RiasecTestSession extends Model
{
    use HasFactory;

    protected $table = 'riasec_test_sessions';

    // ── Statuts de session ─────────────────────────────────────────────────
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_COMPLET  = 'complet';
    const STATUT_ABANDON  = 'abandon';
    const STATUT_EXPIRE   = 'expire';

    // ── Phases du test ─────────────────────────────────────────────────────
    const PHASE_SEED     = 1; // Questions initiales (2 par dimension = 12 total)
    const PHASE_ADAPTIVE = 2; // Sélection adaptative IRT

    // ── Raisons d'arrêt ────────────────────────────────────────────────────
    const STOP_MAX_REACHED        = 'max_reached';
    const STOP_PRECISION_ACHIEVED = 'precision_achieved';
    const STOP_MIN_DIMS_MET       = 'min_dims_met';
    const STOP_ABANDONED          = 'abandoned';

    protected $fillable = [
        'session_token',
        'user_id',
        'session_guest_id',
        'demographic_data',
        'current_scores',
        'administered_question_ids',
        'scores_history',
        'phase',
        'seed_phase_complete',
        'total_questions_asked',
        'min_questions',
        'max_questions',
        'precision_score',
        'score_variance',
        'coherence_score',
        'stop_reason',
        'statut',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'demographic_data'          => 'array',
        'current_scores'            => 'array',
        'administered_question_ids' => 'array',
        'scores_history'            => 'array',
        'seed_phase_complete'       => 'boolean',
        'phase'                     => 'integer',
        'total_questions_asked'     => 'integer',
        'min_questions'             => 'integer',
        'max_questions'             => 'integer',
        'precision_score'           => 'float',
        'score_variance'            => 'float',
        'coherence_score'           => 'float',
        'started_at'                => 'datetime',
        'completed_at'              => 'datetime',
    ];

    // ══════════════════════════════════════════════════════════════════════
    // RELATIONS
    // ══════════════════════════════════════════════════════════════════════

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Toutes les réponses liées à cette session. */
    public function answers(): HasMany
    {
        return $this->hasMany(AnswerRiasec::class, 'test_session_id', 'session_token');
    }

    /** Le profil RIASEC généré à la fin du test. */
    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProfileRiasec::class, 'test_session_id', 'session_token');
    }

    // ══════════════════════════════════════════════════════════════════════
    // SCOPES
    // ══════════════════════════════════════════════════════════════════════

    public function scopeEnCours($q) { return $q->where('statut', self::STATUT_EN_COURS); }
    public function scopeComplets($q) { return $q->where('statut', self::STATUT_COMPLET); }
    public function scopePhaseAdaptive($q) { return $q->where('phase', self::PHASE_ADAPTIVE); }
    public function scopePourUser($q, int $userId) { return $q->where('user_id', $userId); }
    public function scopePourInvite($q, string $guestId) { return $q->where('session_guest_id', $guestId); }

    // ══════════════════════════════════════════════════════════════════════
    // ACCESSEURS & MÉTHODES CALCULÉES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Score de progression du test (0-100%).
     */
    public function getProgressionAttribute(): float
    {
        if ($this->max_questions === 0) return 0.0;
        return round(($this->total_questions_asked / $this->max_questions) * 100, 1);
    }

    /**
     * Durée du test en secondes depuis le début.
     */
    public function getDureeSecondesAttribute(): int
    {
        $end = $this->completed_at ?? now();
        return (int) $this->started_at->diffInSeconds($end);
    }

    /**
     * Durée formatée (mm:ss).
     */
    public function getDureeFormateeAttribute(): string
    {
        $s = $this->duree_secondes;
        return sprintf('%02d:%02d', intdiv($s, 60), $s % 60);
    }

    /**
     * Retourne les scores courants triés par valeur décroissante.
     *
     * @return array<string, float>
     */
    public function getScoresTrIesAttribute(): array
    {
        $scores = $this->current_scores ?? [];
        arsort($scores);
        return $scores;
    }

    /**
     * Retourne le code Holland provisoire (3 lettres) basé sur les scores courants.
     */
    public function getCodeHollandProvisAttribute(): string
    {
        $sorted = $this->scores_tries;
        return implode('', array_slice(array_keys($sorted), 0, 3));
    }

    /**
     * Retourne les IDs de questions administrées sous forme de Collection.
     */
    public function getAdministeredIdsCollection(): Collection
    {
        return collect($this->administered_question_ids ?? []);
    }

    /**
     * Indique si le test est en cours de phase seed.
     */
    public function isInSeedPhase(): bool
    {
        return $this->phase === self::PHASE_SEED && ! $this->seed_phase_complete;
    }

    /**
     * Indique si le test est terminé (statut complet ou abandon).
     */
    public function isTerminated(): bool
    {
        return in_array($this->statut, [self::STATUT_COMPLET, self::STATUT_ABANDON, self::STATUT_EXPIRE]);
    }

    /**
     * Vérifie si une question a déjà été posée dans cette session.
     */
    public function hasQuestionBeenAsked(int $questionId): bool
    {
        return in_array($questionId, $this->administered_question_ids ?? [], true);
    }

    /**
     * Ajoute une question à la liste des questions administrées et incrémente le compteur.
     */
    public function markQuestionAsked(int $questionId): void
    {
        $ids = $this->administered_question_ids ?? [];
        if (! in_array($questionId, $ids, true)) {
            $ids[] = $questionId;
            $this->administered_question_ids = $ids;
            $this->total_questions_asked = count($ids);
        }
    }

    /**
     * Ajoute un point de snapshot à l'historique des scores.
     *
     * @param array<string,float> $scores
     */
    public function appendScoreHistory(array $scores): void
    {
        $history = $this->scores_history ?? [];
        $history[] = [
            'step'      => $this->total_questions_asked,
            'scores'    => $scores,
            'timestamp' => now()->toIso8601String(),
        ];
        // Garde uniquement les 20 derniers snapshots pour limiter la taille JSON
        $this->scores_history = array_slice($history, -20);
    }

    /**
     * Calcule la variance des derniers N snapshots de scores (fenêtre glissante).
     * Utilisé pour le critère d'arrêt par précision.
     *
     * @param int $windowSize Nombre de snapshots à inclure dans la fenêtre
     * @return float Variance moyenne (0=stable, élevé=volatile)
     */
    public function calculateScoreVariance(int $windowSize = 6): float
    {
        $history = $this->scores_history ?? [];
        if (count($history) < 2) return 99.0; // Pas assez de données → considéré volatile

        $window = array_slice($history, -$windowSize);
        $dims   = ['R', 'I', 'A', 'S', 'E', 'C'];
        $variances = [];

        foreach ($dims as $dim) {
            $values = array_map(fn ($h) => $h['scores'][$dim] ?? 0, $window);
            if (count($values) < 2) continue;
            $mean      = array_sum($values) / count($values);
            $variance  = array_sum(array_map(fn ($v) => ($v - $mean) ** 2, $values)) / count($values);
            $variances[] = $variance;
        }

        return empty($variances) ? 0.0 : array_sum($variances) / count($variances);
    }
}
