<?php

namespace App\Http\Controllers\Api\Meal;

use App\Enums\Ingredient;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class IngredientController extends Controller
{
    use ApiResponse;

    public function __invoke(): JsonResponse
    {
        $ingredients = array_map(fn(Ingredient $ingredient) => [
            'value' => $ingredient->value,
            'label' => $ingredient->label(),
            'icon'  => asset('icons/ingredients/' . $ingredient->icon()),
        ], Ingredient::cases());

        return $this->success(data: ['ingredients' => $ingredients]);
    }
}