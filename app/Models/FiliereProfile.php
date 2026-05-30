<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * SIAEPI v5.0 — Profil psychométrique cible d'une filière.
 *
 * Permet au moteur de recommandation de charger dynamiquement
 * les profils RIASEC, GATB et Big Five requis par chaque filière
 * au lieu de les coder en dur.
 */
class FiliereProfile extends Model
{
    protected $table = 'filiere_profiles';

    protected $fillable = [
        'code_filiere',
        'nom_filiere',
        'domaine',
        'riasec_r', 'riasec_i', 'riasec_a', 'riasec_s', 'riasec_e', 'riasec_c',
        'gatb_g_required', 'gatb_v_required', 'gatb_n_required', 'gatb_s_required',
        'employability_index', 'employability_rate', 'growth_rate', 'annual_openings',
        'difficulty_level', 'stress_tolerance',
        'job_demand', 'salary', 'internships', 'market_source', 'market_date', 'market_region',
        'big5_openness', 'big5_conscientiousness', 'big5_extraversion',
        'big5_agreeableness', 'big5_neuroticism',
        'description',
    ];

    protected $casts = [
        'riasec_r' => 'float',
        'riasec_i' => 'float',
        'riasec_a' => 'float',
        'riasec_s' => 'float',
        'riasec_e' => 'float',
        'riasec_c' => 'float',
        'employability_index' => 'float',
        'employability_rate' => 'float',
        'growth_rate' => 'float',
        'annual_openings' => 'integer',
        'job_demand' => 'float',
        'salary' => 'float',
        'internships' => 'float',
        'big5_openness' => 'float',
        'big5_conscientiousness' => 'float',
        'big5_extraversion' => 'float',
        'big5_agreeableness' => 'float',
        'big5_neuroticism' => 'float',
    ];

    /**
     * Retourne le vecteur RIASEC cible sous forme de tableau associatif.
     */
    public function getRiasecVector(): array
    {
        return [
            'R' => $this->riasec_r,
            'I' => $this->riasec_i,
            'A' => $this->riasec_a,
            'S' => $this->riasec_s,
            'E' => $this->riasec_e,
            'C' => $this->riasec_c,
        ];
    }

    /**
     * Retourne les seuils GATB requis.
     */
    public function getGatbRequirements(): array
    {
        return [
            'G' => $this->gatb_g_required,
            'V' => $this->gatb_v_required,
            'N' => $this->gatb_n_required,
            'S' => $this->gatb_s_required,
        ];
    }

    /**
     * Relation vers la filière principale (si la table filieres existe).
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'code_filiere', 'code_filiere');
    }
}
