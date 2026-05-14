<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CvProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'template_name',
        'summary',
        'target_job',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(CvExperience::class)->orderBy('order');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(CvEducation::class)->orderBy('order');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(CvSkill::class)->orderBy('order');
    }

    public function languages(): HasMany
    {
        return $this->hasMany(CvLanguage::class)->orderBy('order');
    }
}
