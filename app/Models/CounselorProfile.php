<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'specialty',
        'experience_years',
        'bio',
        'cv_path',
        'verification_notes',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that owns the counselor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the administrator who approved the profile.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
