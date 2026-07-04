<?php

namespace App\Actions\Category;

use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class GetCategoriesAction
{
    public function __construct(private readonly CategoryRepository $categories) {}

    public function execute(): Collection
    {
        return $this->categories->getAll();
    }
}