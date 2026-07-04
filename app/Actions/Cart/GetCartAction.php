<?php

namespace App\Actions\Cart;

use App\Repositories\CartRepository;

class GetCartAction
{
    public function __construct(private readonly CartRepository $cart) {}

    public function execute(int $userId): array
    {
        $items = $this->cart->getByUser($userId);

        $total = $items->sum(fn ($item) => $item->quantity * $item->meal->price);

        return [
            'items' => $items,
            'total' => round((float) $total, 2),
        ];
    }
}