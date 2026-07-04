<?php

namespace App\Actions\Cart;

use App\Repositories\CartRepository;

class ClearCartAction
{
    public function __construct(private readonly CartRepository $cart) {}

    public function execute(int $userId): void
    {
        $this->cart->clearCart($userId);
    }
}