<?php

namespace App\Actions\Meal;

use App\Models\Meal;
use App\Repositories\MealRepository;

class GetMealAction
{
    public function __construct(private readonly MealRepository $meals) {}

    public function execute(int $id): ?Meal
    {
        return $this->meals->findById($id);
    }
}