<?php

namespace App\Actions\Favorite;

use App\Repositories\FavoriteRepository;

class ToggleFavoriteAction
{
    public function __construct(private readonly FavoriteRepository $favorites) {}

    public function execute(int $userId, int $mealId): array
    {
        $added = $this->favorites->toggle($userId, $mealId);

        return [
            'status'  => $added ? 'added' : 'removed',
            'meal_id' => $mealId,
        ];
    }
}