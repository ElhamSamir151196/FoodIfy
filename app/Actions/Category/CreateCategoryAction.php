<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Repositories\CategoryRepository;
//use Illuminate\Support\Facades\Storage;

class CreateCategoryAction
{
    public function __construct(private readonly CategoryRepository $categories) {}

    public function execute(array $data): Category
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('categories', 'public');
        }

        return $this->categories->create($data);
    }
}