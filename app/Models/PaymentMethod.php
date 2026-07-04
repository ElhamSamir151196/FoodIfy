<?php

namespace App\Models;

use App\Enums\PaymentMethodType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'paymob_token',
        'last_four',
        'card_brand',
        'bank_name',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'type'       => PaymentMethodType::class,
            'is_default' => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ── Scopes ────────────────────────────
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeOfType($query, PaymentMethodType $type)
    {
        return $query->where('type', $type);
    }
}