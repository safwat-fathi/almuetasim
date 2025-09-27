<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Display products filtered by category.
     *
     * @param  string  $categorySlug
     * @return \Illuminate\View\View
     */
    public function show($categorySlug)
    {
        // Find the category by slug
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        // Get products for this category
        $products = Product::where('category_id', $category->id)->with('category')->paginate(12);
        
        return view('category', compact('category', 'products'));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only(['name', 'description']));

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only(['name', 'description']));

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        // Check if category has associated products
        if($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category with associated products!');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }
}