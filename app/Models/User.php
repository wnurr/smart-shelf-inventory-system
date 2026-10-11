<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone_no',
        'email',
        'password',
        'must_change_password',
        'role_id',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role?->position === $role;
    }

    /**
     * Stock changes this user performed or triggered.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    /**
     * Sensor calibrations this user ran.
     */
    public function calibrations(): HasMany
    {
        return $this->hasMany(Calibration::class);
    }

    public function hasPermission(string $permission): bool
    {
    if (! $this->role) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Admin bypass
    |--------------------------------------------------------------------------
    |
    | System Admin can access all administration features.
    |
    */

    if ($this->role->position === 'admin') {
        return true;
    }

    return $this->role
        ->permissions()
        ->where('name', $permission)
        ->exists();
    }
}
