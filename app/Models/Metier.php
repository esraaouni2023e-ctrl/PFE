<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metier extends Model
{
    protected $table = 'metiers';

    protected $fillable = [
        'specialisation_id',
        'title',
        'description',
        'salary_range',
        'employability',
        'secteurs',
        'skills_hard',
        'skills_soft',
        'perspectives'
    ];

    protected $casts = [
        'secteurs' => 'array',
        'skills_hard' => 'array',
        'skills_soft' => 'array',
    ];

    public function specialisation()
    {
        return $this->belongsTo(Specialisation::class, 'specialisation_id');
    }
}
