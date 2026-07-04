<?php

namespace App\Repositories;

//use App\Models\Meal;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class FavoriteRepository
{
    public function __construct(private readonly User $model) {}

    public function getByUser(int $userId): Collection
    {
        return $this->model->findOrFail($userId)->favoriteMeals()->get();
    }

    public function toggle(int $userId, int $mealId): bool
    {
        $user = $this->model->findOrFail($userId);

        $result = $user->favoriteMeals()->toggle($mealId);

        return !empty($result['attached']);
    }

    public function isFavorited(int $userId, int $mealId): bool
    {
        return $this->model
            ->findOrFail($userId)
            ->favoriteMeals()
            ->where('meals.id', $mealId)
            ->exists();
    }
}