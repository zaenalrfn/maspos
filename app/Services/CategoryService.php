<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::select('id', 'name')->latest('created_at')->get();
    }
}
