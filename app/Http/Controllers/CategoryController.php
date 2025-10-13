<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUpdateCategoryRequest;
use Inertia\Inertia;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('CategoryPage/Index', [
            'categories' => Category::with('products')->latest('created_at')->get(),
        ]);
    }

    public function store(CreateUpdateCategoryRequest $request)
    {
        $validated = $request->validated();

        try {
            Category::create($validated);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Kategori berhasil dibuat...');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat kategori: ' . $e->getMessage());
        }
    }
    public function update(CreateUpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        try {
            $category->update($validated);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Kategori berhasil diperbarui...');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()
                ->route('categories.index')
                ->with('success', 'Kategori berhasil dihapus...');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
