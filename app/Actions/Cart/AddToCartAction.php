<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use App\Repositories\CartRepository;

class AddToCartAction
{
    public function __construct(private readonly CartRepository $cart) {}

    public function execute(int $userId, int $mealId, int $quantity): CartItem
    {
        return $this->cart->addItem($userId, $mealId, $quantity);
    }
}