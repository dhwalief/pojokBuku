<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Enums\UserStatus;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
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
     * Get the attributes that should be cast. Includes enum casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
        ];
    }
    
    /**
     * The attributes that should be appended to the model's array form.
     *
     * @return list<string>
     */
    protected function appends(): array
    {
        return [
            'is_admin',
            'is_active',
        ];
    }
    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === UserRole::Admin;
    }
    /**
     * Check if the user is active.
     *
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === UserStatus::Active;
    }
    
    
    /**
     * Get the user's borrows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Borrow>
     */

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
