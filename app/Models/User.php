<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Rôles principaux
    public const ROLE_STUDENT = 'student';
    public const ROLE_COUNSELOR = 'counselor';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_admin',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    /**
     * Check if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Check if the user is a counselor.
     */
    public function isCounselor(): bool
    {
        return $this->role === self::ROLE_COUNSELOR;
    }

    /**
     * Get the profile associated with the user.
     */
    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function portfolioItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PortfolioItem::class);
    }

    public function careerRoadmaps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CareerRoadmap::class);
    }

    public function orientationVoeux(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrientationVoeu::class);
    }

    public function simulationHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SimulationHistory::class);
    }

    // ── Relations RIASEC ──────────────────────────────────────────────────

    /**
     * Tous les profils RIASEC de cet utilisateur (historique de tests).
     */
    public function profilsRiasec(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProfileRiasec::class);
    }

    /**
     * Dernier profil RIASEC complété.
     */
    public function dernierProfilRiasec(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProfileRiasec::class)
                    ->where('statut', ProfileRiasec::STATUT_COMPLET)
                    ->latestOfMany('complete_at');
    }

    /**
     * Toutes les réponses RIASEC de cet utilisateur.
     */
    public function reponsesRiasec(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AnswerRiasec::class);
    }

    /**
     * Check if the user is an admin (separate from role).
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'two_factor_expires_at' => 'datetime',
    ];

    /**
     * Generate a new two factor code.
     */
    public function generateTwoFactorCode(): void
    {
        $this->timestamps = false; // Ne pas mettre à jour updated_at
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
        $this->timestamps = true; // Remettre à true après
    }

    /**
     * Reset the two factor code.
     */
    public function resetTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
        $this->timestamps = true;
    }
}

