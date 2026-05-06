<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialite_id', 'nom', 'etablissement', 'ville', 'duree', 'niveau',
        'description', 'debouches', 'conditions_acces', 'salaire_min', 'salaire_max',
        'secteur', 'icon', 'score_matching',
    ];

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function orientationVoeux(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrientationVoeu::class);
    }
}
