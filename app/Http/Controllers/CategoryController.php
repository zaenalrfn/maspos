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
        return Inertia::render('CategoryPage/Index');
    }

    public function store(CreateUpdateCategoryRequest $request)
    {
        $validated = $request->validated();

        try {
            Category::create($validated);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }
    public function update(CreateUpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        try {
            $category->update($validated);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
