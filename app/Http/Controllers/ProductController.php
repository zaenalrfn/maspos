<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use Illuminate\Support\Facades\Storage;

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
                ->route('dasboard')
                ->with('success', 'Produk berhasil dibuat...');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat produk: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
            return redirect()
                ->route('dashboard')
                ->with('success', 'Produk berhasil dihapus...');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
