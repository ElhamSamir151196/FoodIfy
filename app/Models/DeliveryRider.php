<?php

namespace App\Models;

use App\Enums\TransportationWay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class DeliveryRider extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'transportation_way',
        'avatar',
        'rating',
        'is_available',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'transportation_way' => TransportationWay::class,
            'rating'              => 'decimal:2',
            'is_available'        => 'boolean',
            'password'            => 'hashed',
        ];
    }

    // ── Relationships ─────────────────────
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'rider_id');
    }

    // ── Scopes ────────────────────────────
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeTopRated($query, float $minRating = 4.0)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
