<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousDomaine extends Model
{
    protected $table = 'sous_domaines';

    protected $fillable = ['domaine_id', 'code', 'nom', 'description'];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class, 'domaine_id');
    }

    public function specialisations()
    {
        return $this->hasMany(Specialisation::class, 'sous_domaine_id');
    }

    public function filieres()
    {
        return $this->hasMany(Filiere::class, 'sous_domaine_id');
    }
}
