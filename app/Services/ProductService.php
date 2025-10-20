<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getAllProducts()
    {
        return Product::with(['category:id,name'])
            ->select('id', 'name', 'price', 'image', 'category_id')
            ->latest('created_at')
            ->get();
    }
}
