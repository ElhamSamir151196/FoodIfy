<?php

namespace App\Actions\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;

class CheckoutAction
{
    private const DELIVERY_FEE = 30.00;

    public function __construct(
        private readonly CartRepository  $cart,
        private readonly OrderRepository $orders,
    ) {}

    public function execute(int $userId, array $data): ?Order
    {
        $cartItems = $this->cart->getByUser($userId);

        if ($cartItems->isEmpty()) {
            return null;
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->quantity * $item->meal->price);
        $total    = $subtotal + self::DELIVERY_FEE;

        $order = $this->orders->create([
            'user_id'            => $userId,
            'payment_method_id'  => $data['payment_method_id'] ?? null,
            'delivery_address'   => $data['delivery_address'],
            'notes'              => $data['notes'] ?? null,
            'subtotal'           => $subtotal,
            'delivery_fee'       => self::DELIVERY_FEE,
            'total'              => $total,
            'status'             => OrderStatus::Pending,
        ]);

        $orderItems = $cartItems->map(fn ($item) => [
            'meal_id'    => $item->meal_id,
            'quantity'   => $item->quantity,
            'unit_price' => $item->meal->price,
        ])->all();

        $this->orders->addItems($order, $orderItems);

        $this->cart->clearCart($userId);

        return $this->orders->findById($order->id);
    }
}