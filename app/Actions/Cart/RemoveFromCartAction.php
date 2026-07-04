<?php

namespace App\Actions\Cart;

use App\Repositories\CartRepository;

class RemoveFromCartAction
{
    public function __construct(private readonly CartRepository $cart) {}

    public function execute(int $userId, int $mealId): bool
    {
        return $this->cart->removeItem($userId, $mealId);
    }
}