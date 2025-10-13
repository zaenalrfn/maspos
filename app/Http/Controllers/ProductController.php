<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Products/Index', [
            'categories' => $categories
        ]);
    }

    public function store(CreateProductRequest $request)
    {
        $products = $request->validated();
        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product_images', 'public');
                $products['image'] = $imagePath;
            }

            Product::create($products);

            return redirect()
                ->route('products.create')
                ->with('success', 'Produk berhasil dibuat...');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat produk: ' . $e->getMessage());
        }
    }
}
