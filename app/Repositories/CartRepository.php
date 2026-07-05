<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

class CartRepository
{
    public function __construct(private readonly Cart $model) {}

    public function getByUser(int $userId): Collection
    {
        return $this->model->with('meal')->where('user_id', $userId)->get();
    }

    public function addItem(int $userId, int $mealId, int $quantity = 1): Cart
    {
        $item = $this->model->firstOrNew([
            'user_id' => $userId,
            'meal_id' => $mealId,
        ]);

        $item->quantity = $item->exists ? $item->quantity + $quantity : $quantity;
        $item->save();

        return $item;
    }

    public function updateQuantity(int $userId, int $mealId, int $quantity): ?Cart
    {
        $item = $this->model
            ->where('user_id', $userId)
            ->where('meal_id', $mealId)
            ->first();

        if (!$item) {
            return null;
        }

        $item->update(['quantity' => $quantity]);

        return $item;
    }

    public function removeItem(int $userId, int $mealId): bool
    {
        return (bool) $this->model
            ->where('user_id', $userId)
            ->where('meal_id', $mealId)
            ->delete();
    }

    public function clearCart(int $userId): void
    {
        $this->model->where('user_id', $userId)->delete();
    }

    public function getTotal(int $userId): float
    {
        return (float) $this->model
            ->with('meal')
            ->where('user_id', $userId)
            ->get()
            ->sum(fn (Cart $item) => $item->quantity * $item->meal->price);
    }
}