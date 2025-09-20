<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display products filtered by category.
     *
     * @param  string  $categoryName
     * @return \Illuminate\View\View
     */
    public function show($categoryName)
    {
        // Find the category by name
        $category = Category::where('name', $categoryName)->firstOrFail();
        
        // Get products for this category
        $products = Product::where('category_id', $category->id)->with('category')->paginate(12);
        
        return view('category', compact('category', 'products'));
    }
}