<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'description', 'domaine', 'icon', 'color', 'nb_formations',
    ];

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }
}
