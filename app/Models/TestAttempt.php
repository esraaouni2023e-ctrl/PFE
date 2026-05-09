<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'test_id',
        'score',
        'answers',
        'started_at',
        'completed_at',
        'duration_seconds',
    ];

    protected $casts = [
        'answers' => 'json',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}
