<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Storage;

class UpdateCategoryAction
{
    public function __construct(private readonly CategoryRepository $categories) {}

    public function execute(Category $category, array $data): Category
    {
        if (isset($data['image'])) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $data['image'] = $data['image']->store('categories', 'public');
        }

        return $this->categories->update($category, $data);
    }
}