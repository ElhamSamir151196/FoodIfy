<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Repositories\OrderRepository;

class GetOrderAction
{
    public function __construct(private readonly OrderRepository $orders) {}

    public function execute(int $orderId, int $userId): ?Order
    {
        $order = $this->orders->findById($orderId);

        if (!$order || $order->user_id !== $userId) {
            return null;
        }

        return $order;
    }
}