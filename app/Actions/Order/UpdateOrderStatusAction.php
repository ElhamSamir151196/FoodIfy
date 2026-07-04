<?php

namespace App\Actions\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatusNotification;
use App\Repositories\OrderRepository;

class UpdateOrderStatusAction
{
    public function __construct(private readonly OrderRepository $orders) {}

    public function execute(int $orderId, OrderStatus $status): ?Order
    {
        $updated = $this->orders->updateStatus($orderId, $status);

        if (!$updated) {
            return null;
        }

        $order = $this->orders->findById($orderId);
        $order->user->notify(new OrderStatusNotification($order));

        return $order;
    }
}