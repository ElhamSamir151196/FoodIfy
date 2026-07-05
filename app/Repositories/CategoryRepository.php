<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function __construct(private readonly Category $model) {}

    public function getAll(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

   /* public function findById(int $id): ?Category
    {
        return $this->model->find($id);
    }*/

    public function findById(int $id): ?Category
    {
        return $this->model->with('meals.reviews')->find($id);
    }

    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        return (bool) $category->delete();
    }
}