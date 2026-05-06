<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceCriterion extends Model
{
    protected $fillable = [
        'reference_section_id',
        'subject',
        'coefficient',
    ];

    public function section()
    {
        return $this->belongsTo(ReferenceSection::class, 'reference_section_id');
    }
}
