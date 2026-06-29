<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // ─────────────────────────────────────────
    // Fillable
    // ─────────────────────────────────────────
    protected $fillable = [
        'full_name',  'email', 'password',
        'phone', 'avatar', 'role',
        'address','birth_date', 'is_active',
    ];

    // ─────────────────────────────────────────
    // Hidden
    // ─────────────────────────────────────────
    protected $hidden = [  'password', 'remember_token' ];

    // ─────────────────────────────────────────
    // Casts
    // ─────────────────────────────────────────
    protected $casts = [
        'password'          => 'hashed',
        'role'              => UserRole::class,
        'is_active'         => 'boolean',
        'birth_date'        => 'date',
        'phone_verified_at' => 'datetime', // ✅

    ];

    // ─────────────────────────────────────────
    // Role Helpers
    // ─────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isClient(): bool
    {
        return $this->role === UserRole::Client;
    }

    // ─────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeClients($query)
    {
        return $query->where('role', UserRole::Client);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', UserRole::Admin);
    }

    // ─────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}