<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Enums\TrackingStatus;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrderRepository
{
    public function __construct(private readonly Order $model) {}

    public function create(array $data): Order
    {
        return $this->model->create($data);
    }

    public function addItems(Order $order, array $items): void
    {
        $order->items()->createMany($items);
    }

    public function findById(int $id): ?Order
    {
        return $this->model->with(['items.meal', 'rider', 'paymentMethod', 'user'])->find($id);
    }

    public function getByUser(int $userId, ?OrderStatus $status = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with(['items.meal', 'rider'])
            ->where('user_id', $userId)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate($perPage);
    }

    public function getAllFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'rider'])
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['user_id'] ?? null, fn ($q, $userId) => $q->where('user_id', $userId))
            ->when($filters['date_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate($perPage);
    }

    public function updateStatus(int $orderId, OrderStatus $status): bool
    {
        return (bool) $this->model->whereKey($orderId)->update(['status' => $status]);
    }

    public function updateTrackingStatus(int $orderId, TrackingStatus $status): bool
    {
        return (bool) $this->model->whereKey($orderId)->update(['tracking_status' => $status]);
    }

    public function assignRider(int $orderId, int $riderId): bool
    {
        return (bool) $this->model->whereKey($orderId)->update([
            'rider_id'        => $riderId,
            'tracking_status' => TrackingStatus::Assigned,
        ]);
    }

    public function markAsPaid(int $orderId): bool
    {
        return (bool) $this->model->whereKey($orderId)->update([
            'paid_at' => now(),
            'status'  => OrderStatus::Confirmed,
        ]);
    }

    // ── Stats ──────────────────────────────
    public function countTotal(): int
    {
        return $this->model->count();
    }

    public function countToday(): int
    {
        return $this->model->whereDate('created_at', today())->count();
    }

    public function revenueToday(): float
    {
        return (float) $this->model
            ->whereNotNull('paid_at')
            ->whereDate('paid_at', today())
            ->sum('total');
    }

    public function revenueThisMonth(): float
    {
        return (float) $this->model
            ->whereNotNull('paid_at')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total');
    }

    public function countByStatus(): Collection
    {
        return $this->model
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    }
}