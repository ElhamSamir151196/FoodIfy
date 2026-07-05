<?php

namespace App\Http\Controllers\Api\Meal;

use App\Actions\Meal\ToggleAvailabilityAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ToggleAvailabilityController extends Controller
{
    use ApiResponse;

    public function __invoke(Meal $meal, ToggleAvailabilityAction $action): JsonResponse
    {
        $meal = $action->execute($meal);

        return $this->success(
            data: ['meal' => new MealResource($meal)],
            message: 'Meal availability updated.'
        );
    }
}