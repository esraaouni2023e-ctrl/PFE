<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    protected $fillable = [
        'code_filiere', 
        'nom_filiere', 
        'universite',
        'etablissement', 
        'sdo_2023', 
        'sdo_2024', 
        'sdo_2025', 
        'domaine'
    ];
}
