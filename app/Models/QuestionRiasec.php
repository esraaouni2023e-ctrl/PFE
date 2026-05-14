<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle QuestionRiasec — Question de la banque du test Holland RIASEC.
 *
 * @property int         $id
 * @property string      $dimension       Lettre RIASEC (R|I|A|S|E|C)
 * @property string      $texte_fr        Libellé français
 * @property string|null $texte_ar        Libellé arabe
 * @property string      $type_reponse    likert | boolean | choice
 * @property array|null  $options         Options JSON pour type "choice"
 * @property int         $poids           Pondération 1-3
 * @property int         $ordre           Ordre d'affichage
 * @property bool        $actif
 * @property string|null $source          Référence psychométrique
 * @property string      $version
 *
 * @property-read string $libelle_dimension   Nom complet de la dimension
 * @property-read string $texte               Texte selon la locale courante
 */
class QuestionRiasec extends Model
{
    use HasFactory;
    
    // ── Constantes de dimension ────────────────────────────────────────────
    const DIM_R = 'R'; // Réaliste
    const DIM_I = 'I'; // Investigateur
    const DIM_A = 'A'; // Artistique
    const DIM_S = 'S'; // Social
    const DIM_E = 'E'; // Entreprenant
    const DIM_C = 'C'; // Conventionnel

    const DIMENSIONS = [
        self::DIM_R => 'Réaliste',
        self::DIM_I => 'Investigateur',
        self::DIM_A => 'Artistique',
        self::DIM_S => 'Social',
        self::DIM_E => 'Entreprenant',
        self::DIM_C => 'Conventionnel',
    ];

    const TYPES_REPONSE = ['likert', 'boolean', 'choice'];

    // ── Likert : libellés des niveaux ─────────────────────────────────────
    const LIKERT_LABELS = [
        1 => 'Pas du tout',
        2 => 'Plutôt non',
        3 => 'Neutre',
        4 => 'Plutôt oui',
        5 => 'Tout à fait',
    ];

    protected $table = 'riasec_questions';

    protected $fillable = [
        'dimension',
        'bloc',
        'categorie',
        'texte_fr',
        'texte_ar',
        'type_reponse',
        'options',
        'poids',
        'ordre',
        'actif',
        'source',
        'difficulty',
        'discrimination',
        'is_reverse',
        'calibration_version',
        'is_seed',
        'version',
        'bacs_cibles',
    ];

    protected $casts = [
        'options'             => 'array',
        'bacs_cibles'         => 'array',
        'actif'               => 'boolean',
        'is_reverse'          => 'boolean',
        'is_seed'             => 'boolean',
        'poids'               => 'integer',
        'ordre'               => 'integer',
        'difficulty'          => 'float',
        'discrimination'      => 'float',
    ];

    protected $hidden = [];

    // ══════════════════════════════════════════════════════════════════════
    // RELATIONS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Toutes les réponses données à cette question.
     */
    public function reponses(): HasMany
    {
        return $this->hasMany(AnswerRiasec::class, 'question_id');
    }

    // ══════════════════════════════════════════════════════════════════════
    // SCOPES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Filtre les questions actives uniquement.
     */
    public function scopeActives(Builder $query): Builder
    {
        return $query->where('actif', true);
    }

    /**
     * Filtre par dimension RIASEC (ex: scopeDimension('I')).
     */
    public function scopeDimension(Builder $query, string $dim): Builder
    {
        return $query->where('dimension', strtoupper($dim));
    }

    /**
     * Retourne les questions dans l'ordre d'affichage configuré.
     */
    public function scopeOrdonnes(Builder $query): Builder
    {
        return $query->orderBy('ordre')->orderBy('id');
    }

    /**
     * Filtre par type de réponse.
     */
    public function scopeDeType(Builder $query, string $type): Builder
    {
        return $query->where('type_reponse', $type);
    }

    // ══════════════════════════════════════════════════════════════════════
    // ACCESSEURS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Libellé complet de la dimension (ex: "Investigateur").
     */
    public function getLibelleDimensionAttribute(): string
    {
        return self::DIMENSIONS[$this->dimension] ?? $this->dimension;
    }

    /**
     * Texte de la question selon la locale de l'application.
     * Fallback vers français si la traduction arabe est absente.
     */
    public function getTexteAttribute(): string
    {
        if (app()->getLocale() === 'ar' && !empty($this->texte_ar)) {
            return $this->texte_ar;
        }
        return $this->texte_fr;
    }

    /**
     * Indique si la question admet une pondération renforcée.
     */
    public function getEstPondereeAttribute(): bool
    {
        return $this->poids > 1;
    }

    // ══════════════════════════════════════════════════════════════════════
    // MÉTHODES UTILITAIRES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Retourne les libellés de réponse adaptés au type de cette question.
     *
     * @return array<int|string, string>
     */
    public function getLabelsReponse(): array
    {
        return match ($this->type_reponse) {
            'likert'  => self::LIKERT_LABELS,
            'boolean' => [0 => 'Non', 1 => 'Oui'],
            'choice'  => collect($this->options ?? [])
                            ->pluck('label', 'valeur')
                            ->all(),
            default   => [],
        };
    }

    /**
     * Valide qu'une valeur de réponse est acceptable pour ce type de question.
     */
    public function valeurEstValide(int $valeur): bool
    {
        return match ($this->type_reponse) {
            'likert'  => $valeur >= 1 && $valeur <= 5,
            'boolean' => in_array($valeur, [0, 1]),
            'choice'  => collect($this->options ?? [])
                            ->pluck('valeur')
                            ->contains($valeur),
            default   => false,
        };
    }
}
