<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceSection extends Model
{
    protected $fillable = [
        'name',
        'description',
        'required_bac_score',
    ];

    public function criteria()
    {
        return $this->hasMany(ReferenceCriterion::class);
    }
}
