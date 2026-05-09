<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'counselor_id',
        'student_id',
        'scheduled_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
