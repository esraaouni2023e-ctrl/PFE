<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::saved(fn() => \Illuminate\Support\Facades\Cache::forget('siaepi_filieres'));
        static::deleted(fn() => \Illuminate\Support\Facades\Cache::forget('siaepi_filieres'));
    }

    protected $fillable = [
        'code_filiere', 
        'nom_filiere', 
        'universite',
        'etablissement', 
        'sdo_2023', 
        'sdo_2024', 
        'sdo_2025', 
        'domaine',
        'domaine_id',
        'sous_domaine_id',
        'specialisation_id',
        'code_riasec',
        'taux_employabilite',
        'croissance_domaine',
        'type_bac',
        'g_requis',
        'v_requis',
        'n_requis',
        's_requis'
    ];

    /**
     * Relation vers le profil psychométrique et requis cognitifs.
     */
    public function profile()
    {
        return $this->hasOne(FiliereProfile::class, 'code_filiere', 'code_filiere');
    }

    /**
     * Relation vers le domaine de la taxonomie.
     */
    public function domaineRelation()
    {
        return $this->belongsTo(Domaine::class, 'domaine_id');
    }

    /**
     * Relation vers le sous-domaine de la taxonomie.
     */
    public function sousDomaine()
    {
        return $this->belongsTo(SousDomaine::class, 'sous_domaine_id');
    }

    /**
     * Relation vers la spécialisation de la taxonomie.
     */
    public function specialisation()
    {
        return $this->belongsTo(Specialisation::class, 'specialisation_id');
    }

    /**
     * Accesseur dynamique : retourne les métiers et compétences associés à cette filière.
     * Si des métiers spécifiques existent, ils sont renvoyés, sinon on hérite de la spécialisation.
     */
    public function getCareersAttribute(): array
    {
        $careersData = [];
        
        if ($this->specialisation && $this->specialisation->metiers->isNotEmpty()) {
            foreach ($this->specialisation->metiers as $metier) {
                $careersData[] = [
                    'title' => $metier->title,
                    'description' => $metier->description,
                    'salary_range' => $metier->salary_range,
                    'employability' => $metier->employability,
                    'secteurs' => $metier->secteurs ?? [],
                    'skills_hard' => $metier->skills_hard ?? [],
                    'skills_soft' => $metier->skills_soft ?? [],
                    'perspectives' => $metier->perspectives
                ];
            }
        }
        
        return $careersData;
    }
}
