<?php

namespace App\Http\Controllers\Api;

use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\GetCategoriesAction;
use App\Actions\Category\GetCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    // ── Public ────────────────────────────
    public function index(GetCategoriesAction $action): JsonResponse
    {
        $categories = $action->execute();

        return $this->success(data: ['categories' => CategoryResource::collection($categories)]);
    }

    public function show(int $id, GetCategoryAction $action): JsonResponse
    {
        $category = $action->execute($id);

        if (!$category) {
            return $this->error('Category not found.', 404);
        }

        return $this->success(data: ['category' => new CategoryResource($category)]);
    }

    // ── Admin ─────────────────────────────
    public function store(StoreCategoryRequest $request, CreateCategoryAction $action): JsonResponse
    {
        $category = $action->execute($request->validated());

        return $this->success(
            data: ['category' => new CategoryResource($category)],
            message: 'Category created successfully.',
            statusCode: 201
        );
    }

    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action): JsonResponse
    {
        $category = $action->execute($category, $request->validated());

        return $this->success(
            data: ['category' => new CategoryResource($category)],
            message: 'Category updated successfully.'
        );
    }

    public function destroy(Category $category, DeleteCategoryAction $action): JsonResponse
    {
        $action->execute($category);

        return $this->success(message: 'Category deleted successfully.');
    }
}