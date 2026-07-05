<?php

namespace App\Repositories;

use App\Models\Meal;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;

class MealRepository
{
    public function __construct(
        private readonly Meal $model,
        private readonly OrderItem $orderItemModel,
    ) {}

    public function getAll(): Collection
    {
        return $this->model->with('category')->available()->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->with('category')->available()->inCategory($categoryId)->get();
    }

    public function findById(int $id): ?Meal
    {
        return $this->model->with(['category', 'reviews.user'])->find($id);
    }

    public function search(string $term): Collection
    {
        return $this->model
            ->with('category')
            ->available()
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                      ->orWhere('description', 'like', "%{$term}%");
            })
            ->get();
    }

    public function create(array $data): Meal
    {
        return $this->model->create($data);
    }

    public function update(Meal $meal, array $data): Meal
    {
        $meal->update($data);

        return $meal->fresh('category');
    }

    public function delete(Meal $meal): bool
    {
        return (bool) $meal->delete();
    }

    public function toggleAvailability(Meal $meal): Meal
    {
        $meal->update(['is_available' => !$meal->is_available]);

        return $meal->fresh('category');
    }

    // ── Stats ──────────────────────────────
    public function topSelling(int $limit = 5): Collection
    {
        return $this->orderItemModel
            ->selectRaw('meal_id, SUM(quantity) as total_ordered')
            ->with('meal')
            ->groupBy('meal_id')
            ->orderByDesc('total_ordered')
            ->limit($limit)
            ->get();
    }
}