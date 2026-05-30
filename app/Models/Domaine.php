<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domaine extends Model
{
    protected $table = 'domaines';

    protected $fillable = ['code', 'nom', 'description', 'icon'];

    public function sousDomaines()
    {
        return $this->hasMany(SousDomaine::class, 'domaine_id');
    }

    public function filieres()
    {
        return $this->hasMany(Filiere::class, 'domaine_id');
    }
}
