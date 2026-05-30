<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentInteraction extends Model
{
    protected $table = 'student_interactions';

    protected $fillable = [
        'user_id',
        'filiere_code',
        'action', // view, save, ignore
        'weight',
    ];

    /**
     * Relation vers l'étudiant.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation vers la filière.
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_code', 'code_filiere');
    }
}
