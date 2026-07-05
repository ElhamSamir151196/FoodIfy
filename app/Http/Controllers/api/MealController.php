<?php

namespace App\Http\Controllers\Api;

use App\Actions\Meal\CreateMealAction;
use App\Actions\Meal\DeleteMealAction;
use App\Actions\Meal\GetMealAction;
use App\Actions\Meal\GetMealsAction;
use App\Actions\Meal\UpdateMealAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Meal\StoreMealRequest;
use App\Http\Requests\Meal\UpdateMealRequest;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    use ApiResponse;

    // ── Public ────────────────────────────
    public function index(Request $request, GetMealsAction $action): JsonResponse
    {
        $meals = $action->execute(
            categoryId: $request->integer('category_id') ?: null,
            search: $request->query('search') ?: null,
        );

        return $this->success(data: ['meals' => MealResource::collection($meals)]);
    }

    public function show(int $id, GetMealAction $action): JsonResponse
    {
        $meal = $action->execute($id);

        if (!$meal) {
            return $this->error('Meal not found.', 404);
        }

        return $this->success(data: ['meal' => new MealResource($meal)]);
    }

    // ── Admin ─────────────────────────────
    public function store(StoreMealRequest $request, CreateMealAction $action): JsonResponse
    {
        $meal = $action->execute($request->validated());

        return $this->success(
            data: ['meal' => new MealResource($meal->load(['category']))],
            message: 'Meal created successfully.',
            statusCode: 201
        );
    }

    public function update(UpdateMealRequest $request, Meal $meal, UpdateMealAction $action): JsonResponse
    {
        $meal = $action->execute($meal, $request->validated());

        return $this->success(
            data: ['meal' => new MealResource($meal)],
            message: 'Meal updated successfully.'
        );
    }

    public function destroy(Meal $meal, DeleteMealAction $action): JsonResponse
    {
        $action->execute($meal);

        return $this->success(message: 'Meal deleted successfully.');
    }

}