<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Ingredient\CreateIngredientAction;
use App\Actions\Ingredient\GetIngredientsAction;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    use ApiResponse;

    public function index(GetIngredientsAction $action): JsonResponse
    {
        return $this->success(data: ['ingredients' => $action->execute()]);
    }

    public function store(Request $request, CreateIngredientAction $action): JsonResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:100', 'unique:ingredients,name']]);

        $ingredient = $action->execute($request->name);

        return $this->success(data: ['ingredient' => $ingredient], message: 'Ingredient created.', statusCode: 201);
    }
}