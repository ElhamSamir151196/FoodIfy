<?php

namespace App\Actions\Meal;

use App\Models\Meal;
use App\Repositories\MealRepository;
use Illuminate\Support\Facades\Storage;

class UpdateMealAction
{
    public function __construct(private readonly MealRepository $meals) {}

    public function execute(Meal $meal, array $data): Meal
    {
        if (isset($data['image'])) {
            Storage::disk('public')->delete($meal->image);
            $data['image'] = $data['image']->store('meals', 'public');
        }

        return $this->meals->update($meal, $data);
    }
}