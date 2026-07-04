<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Storage;

class DeleteCategoryAction
{
    public function __construct(private readonly CategoryRepository $categories) {}

    public function execute(Category $category): bool
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return $this->categories->delete($category);
    }
}