<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Repositories\CategoryRepository;

class GetCategoryAction
{
    public function __construct(private readonly CategoryRepository $categories) {}

    public function execute(int $id): ?Category
    {
        return $this->categories->findById($id);
    }
}