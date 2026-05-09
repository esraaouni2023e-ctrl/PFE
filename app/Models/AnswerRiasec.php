<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle AnswerRiasec — Réponse individuelle d'un utilisateur à une question RIASEC.
 *
 * Supporte deux modes d'identification :
 *  - Authentifié : user_id renseigné, session_guest_id null
 *  - Invité      : user_id null, session_guest_id = ID session PHP
 *
 * Une session de test est regroupée par test_session_id (UUID).
 *
 * @property int         $id
 * @property string      $test_session_id   UUID de la session de test
 * @property int|null    $user_id
 * @property string|null $session_guest_id
 * @property int         $question_id
 * @property int         $valeur            Valeur brute de la réponse
 * @property int|null    $temps_reponse_ms
 *
 * @property-read QuestionRiasec $question
 * @property-read User|null      $user
 * @property-read int            $valeur_ponderee  Valeur × poids de la question
 */
class AnswerRiasec extends Model
{
    protected $table = 'riasec_answers';

    protected $fillable = [
        'test_session_id',
        'user_id',
        'session_guest_id',
        'question_id',
        'valeur',
        'temps_reponse_ms',
    ];

    protected $casts = [
        'valeur'           => 'integer',
        'temps_reponse_ms' => 'integer',
    ];

    // ══════════════════════════════════════════════════════════════════════
    // RELATIONS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * La question à laquelle cette réponse correspond.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(QuestionRiasec::class, 'question_id');
    }

    /**
     * L'utilisateur authentifié (null si invité).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Le profil RIASEC associé à cette session.
     */
    public function profil(): BelongsTo
    {
        return $this->belongsTo(ProfileRiasec::class, 'test_session_id', 'test_session_id');
    }

    // ══════════════════════════════════════════════════════════════════════
    // SCOPES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Filtre par session de test (UUID).
     */
    public function scopeSession(Builder $query, string $sessionId): Builder
    {
        return $query->where('test_session_id', $sessionId);
    }

    /**
     * Filtre les réponses d'un utilisateur authentifié.
     */
    public function scopePourUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filtre les réponses d'un invité par son ID de session PHP.
     */
    public function scopePourInvite(Builder $query, string $guestId): Builder
    {
        return $query->whereNull('user_id')
                     ->where('session_guest_id', $guestId);
    }

    /**
     * Joint les données de la question pour éviter le N+1.
     */
    public function scopeAvecQuestion(Builder $query): Builder
    {
        return $query->with('question');
    }

    // ══════════════════════════════════════════════════════════════════════
    // ACCESSEURS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Valeur pondérée = valeur × poids de la question.
     * Utile pour le calcul du score final.
     */
    public function getValeurPondereeAttribute(): int
    {
        $poids = $this->question?->poids ?? 1;
        return $this->valeur * $poids;
    }

    /**
     * Dimension RIASEC de cette réponse (raccourci).
     */
    public function getDimensionAttribute(): ?string
    {
        return $this->question?->dimension;
    }

    /**
     * Indique si la réponse vient d'un invité.
     */
    public function getEstInviteAttribute(): bool
    {
        return $this->user_id === null;
    }

    // ══════════════════════════════════════════════════════════════════════
    // MÉTHODES STATIQUES DE FACTORY
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Crée ou met à jour une réponse pour une session donnée.
     * Garantit l'unicité (test_session_id, question_id).
     *
     * @param string   $sessionId   UUID de la session
     * @param int      $questionId
     * @param int      $valeur
     * @param int|null $userId
     * @param string|null $guestId
     */
    public static function enregistrer(
        string  $sessionId,
        int     $questionId,
        int     $valeur,
        ?int    $userId  = null,
        ?string $guestId = null,
        ?int    $tempsMs = null
    ): self {
        return static::updateOrCreate(
            [
                'test_session_id' => $sessionId,
                'question_id'     => $questionId,
            ],
            [
                'user_id'          => $userId,
                'session_guest_id' => $guestId,
                'valeur'           => $valeur,
                'temps_reponse_ms' => $tempsMs,
            ]
        );
    }
}
