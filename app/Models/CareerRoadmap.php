<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerRoadmap extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_job',
        'steps',
        'status',
    ];

    protected $casts = [
        'steps' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
