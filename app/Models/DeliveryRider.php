<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryRider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'avatar',
        'rating',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'rating'       => 'decimal:2',
            'is_available' => 'boolean',
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