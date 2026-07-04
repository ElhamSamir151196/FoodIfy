<?php

namespace App\Repositories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Collection;

class IngredientRepository
{
    public function __construct(private readonly Ingredient $model) {}

    public function getAll(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

    public function create(array $data): Ingredient
    {
        return $this->model->create($data);
    }
}