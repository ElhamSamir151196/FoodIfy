<?php

namespace App\Actions\Meal;

use App\Repositories\MealRepository;
use Illuminate\Database\Eloquent\Collection;

class GetMealsAction
{
    public function __construct(private readonly MealRepository $meals) {}

    public function execute(?int $categoryId = null, ?string $search = null): Collection
    {
        if ($search) {
            return $this->meals->search($search);
        }

        if ($categoryId) {
            return $this->meals->getByCategory($categoryId);
        }

        return $this->meals->getAll();
    }
}