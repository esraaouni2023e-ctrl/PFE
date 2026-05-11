<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'sujet',
        'message',
        'lire',
    ];

    protected $casts = [
        'lire' => 'boolean',
    ];

    public function scopeNonLus($query)
    {
        return $query->where('lire', 0);
    }

    public function scopeLus($query)
    {
        return $query->where('lire', 1);
    }
}
