<?php

namespace App\Actions\Meal;

use App\Models\Meal;
use App\Repositories\MealRepository;

class CreateMealAction
{
    public function __construct(private readonly MealRepository $meals) {}

    public function execute(array $data): Meal
    {
        $data['image'] = $data['image']->store('meals', 'public');

        return $this->meals->create($data);
    }
}