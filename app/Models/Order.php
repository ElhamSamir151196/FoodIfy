<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\TrackingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rider_id',
        'payment_method_id',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
        'notes',
        'subtotal',
        'delivery_fee',
        'total',
        'status',
        'tracking_status',
        'estimated_minutes',
        'paid_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'delivery_lat'     => 'decimal:7',
            'delivery_lng'     => 'decimal:7',
            'subtotal'         => 'decimal:2',
            'delivery_fee'     => 'decimal:2',
            'total'            => 'decimal:2',
            'status'           => OrderStatus::class,
            'tracking_status'  => TrackingStatus::class,
            'paid_at'          => 'datetime',
            'cancelled_at'     => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(DeliveryRider::class, 'rider_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    // ── Scopes ────────────────────────────
    public function scopeStatus($query, OrderStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [OrderStatus::Delivered, OrderStatus::Cancelled]);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('rider_id');
    }
}