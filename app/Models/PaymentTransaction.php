<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'paymob_order_id',
        'paymob_transaction_id',
        'amount',
        'currency',
        'status',
        'paymob_response',
    ];

    protected function casts(): array
    {
        return [
            'amount'          => 'decimal:2',
            'status'          => PaymentStatus::class,
            'paymob_response' => 'array',
        ];
    }

    // ── Relationships ─────────────────────
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // ── Scopes ────────────────────────────
    public function scopeStatus($query, PaymentStatus $status)
    {
        return $query->where('status', $status);
    }
}