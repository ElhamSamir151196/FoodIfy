<?php

namespace App\Actions\Ingredient;

use App\Models\Ingredient;
use App\Repositories\IngredientRepository;

class CreateIngredientAction
{
    public function __construct(private readonly IngredientRepository $ingredients) {}

    public function execute(string $name): Ingredient
    {
        return $this->ingredients->create(['name' => $name]);
    }
}