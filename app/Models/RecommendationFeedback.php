<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendationFeedback extends Model
{
    protected $table = 'recommendation_feedbacks';

    protected $fillable = [
        'user_id',
        'filiere_code',
        'rating',
        'is_relevant',
        'comment',
    ];

    protected $casts = [
        'is_relevant' => 'boolean',
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_code', 'code_filiere');
    }
}
