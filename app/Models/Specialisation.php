<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialisation extends Model
{
    protected $table = 'specialisations';

    protected $fillable = ['sous_domaine_id', 'code', 'nom', 'description'];

    public function sousDomaine()
    {
        return $this->belongsTo(SousDomaine::class, 'sous_domaine_id');
    }

    public function metiers()
    {
        return $this->hasMany(Metier::class, 'specialisation_id');
    }

    public function filieres()
    {
        return $this->hasMany(Filiere::class, 'specialisation_id');
    }
}
