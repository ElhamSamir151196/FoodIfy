<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Repositories\OrderRepository;

class AssignRiderAction
{
    public function __construct(private readonly OrderRepository $orders) {}

    public function execute(int $orderId, int $riderId): ?Order
    {
        $updated = $this->orders->assignRider($orderId, $riderId);

        if (!$updated) {
            return null;
        }

        return $this->orders->findById($orderId);
    }
}