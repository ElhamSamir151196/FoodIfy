<?php

namespace App\Actions\Favorite;

use App\Repositories\FavoriteRepository;
use Illuminate\Database\Eloquent\Collection;

class GetFavoritesAction
{
    public function __construct(private readonly FavoriteRepository $favorites) {}

    public function execute(int $userId): Collection
    {
        return $this->favorites->getByUser($userId);
    }
}