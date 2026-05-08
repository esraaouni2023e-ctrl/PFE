<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Filiere extends Model
{
    // ── Catégories disponibles ─────────────────────────────────────────────
    const CATEGORIES = [
        'INFO'  => 'Informatique & Numérique',
        'TECH'  => 'Sciences & Technologies',
        'ECO'   => 'Économie & Gestion',
        'EXP'   => 'Sciences Expérimentales',
        'SPORT' => 'Sport & Kinésithérapie',
        'MAT'   => 'Mathématiques & Physique',
        'LET'   => 'Lettres & Sciences Humaines',
    ];

    // ── Masse assignment ───────────────────────────────────────────────────
    protected $fillable = [
        'code_filiere',
        'categorie',
        'nom_filiere',
        'universite',
        'etablissement',
        'sdo_2023',
        'sdo_2024',
        'sdo_2025',
        'code_riasec',
        'taux_employabilite',
        'croissance_domaine',
        'alignment_national',
        'source',
    ];

    // ── Casts automatiques ─────────────────────────────────────────────────
    protected $casts = [
        'sdo_2023'           => 'float',
        'sdo_2024'           => 'float',
        'sdo_2025'           => 'float',
        'taux_employabilite' => 'float',
        'croissance_domaine' => 'float',
        'alignment_national' => 'float',
    ];

    // ══════════════════════════════════════════════════════════════════════
    // ACCESSEURS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * SDO le plus récent disponible (2025 > 2024 > 2023).
     */
    public function getSdoActuelAttribute(): ?float
    {
        return $this->sdo_2025 ?? $this->sdo_2024 ?? $this->sdo_2023;
    }

    /**
     * Libellé de catégorie lisible.
     */
    public function getCategorieLibelleAttribute(): string
    {
        return self::CATEGORIES[$this->categorie] ?? $this->categorie;
    }

    /**
     * Taux d'employabilité en pourcentage formaté (ex: "87%").
     */
    public function getTauxEmployabilitePctAttribute(): string
    {
        return $this->taux_employabilite !== null
            ? round($this->taux_employabilite * 100) . '%'
            : 'N/A';
    }

    /**
     * Premier code RIASEC dominant (première lettre).
     */
    public function getRiasecPrimaryAttribute(): ?string
    {
        return $this->code_riasec ? strtoupper($this->code_riasec[0]) : null;
    }

    // ══════════════════════════════════════════════════════════════════════
    // SCOPES
    // ══════════════════════════════════════════════════════════════════════

    /** Filtre par catégorie. */
    public function scopeCategorie(Builder $query, string $categorie): Builder
    {
        return $query->where('categorie', strtoupper($categorie));
    }

    /** Filtre par code RIASEC (contient). */
    public function scopeRiasec(Builder $query, string $code): Builder
    {
        return $query->where('code_riasec', 'like', "%{$code}%");
    }

    /** Filtre par SDO minimum (année 2025 par défaut). */
    public function scopeSdoMin(Builder $query, float $min, int $annee = 2025): Builder
    {
        return $query->where("sdo_{$annee}", '>=', $min);
    }

    /** Tri par taux d'employabilité décroissant. */
    public function scopeMeilleureEmployabilite(Builder $query): Builder
    {
        return $query->orderByDesc('taux_employabilite');
    }

    /** Filtre par établissement (recherche souple). */
    public function scopeEtablissement(Builder $query, string $terme): Builder
    {
        return $query->where('etablissement', 'like', "%{$terme}%")
                     ->orWhere('universite', 'like', "%{$terme}%");
    }
}
