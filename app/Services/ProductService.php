<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getAllProducts()
    {
        return Product::select('id', 'name', 'price', 'image')->latest('created_at')->get();
    }
}
