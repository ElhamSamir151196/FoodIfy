<?php

namespace App\Actions\Meal;

use App\Models\Meal;
use App\Repositories\MealRepository;
use Illuminate\Support\Facades\Storage;

class DeleteMealAction
{
    public function __construct(private readonly MealRepository $meals) {}

    public function execute(Meal $meal): bool
    {
        Storage::disk('public')->delete($meal->image);

        return $this->meals->delete($meal);
    }
}