<?php

namespace App\Actions\Meal;

use App\Models\Meal;
use App\Repositories\MealRepository;

class ToggleAvailabilityAction
{
    public function __construct(private readonly MealRepository $meals) {}

    public function execute(Meal $meal): Meal
    {
        return $this->meals->toggleAvailability($meal);
    }
}