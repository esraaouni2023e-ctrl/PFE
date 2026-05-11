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
    public const ROLE_SUPER_ADMIN = 'super_admin';

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
        'avatar',
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
     * Check if the user is the Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
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
     * Check if the user is an admin (Super Admin only).
     */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Enforce unique Super Admin.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if ($user->role === self::ROLE_SUPER_ADMIN) {
                if (static::where('role', self::ROLE_SUPER_ADMIN)->exists()) {
                    throw new \Exception('Il ne peut exister qu\'un seul Super Admin.');
                }
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('role') && $user->role === self::ROLE_SUPER_ADMIN) {
                if (static::where('role', self::ROLE_SUPER_ADMIN)->where('id', '!=', $user->id)->exists()) {
                    throw new \Exception('Il ne peut exister qu\'un seul Super Admin.');
                }
            }
        });
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
    ];
}

