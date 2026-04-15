<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'skills',
        'interests',
        'strengths',
        'ai_score',
        'summary',
        'total_xp',
        'settings',
        'counselor_observations',
        'coaching_plan',
        'status',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
