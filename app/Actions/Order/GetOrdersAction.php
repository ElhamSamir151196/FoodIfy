<?php

namespace App\Actions\Order;

use App\Enums\OrderStatus;
use App\Repositories\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class GetOrdersAction
{
    public function __construct(private readonly OrderRepository $orders) {}

    public function execute(int $userId, ?string $status = null): LengthAwarePaginator
    {
        $statusEnum = $status ? OrderStatus::tryFrom($status) : null;

        return $this->orders->getByUser($userId, $statusEnum);
    }
}