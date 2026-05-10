<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'file_path',
        'ai_analysis_summary',
        'extracted_skills',
    ];

    protected $casts = [
        'extracted_skills' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
