<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with categories and products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch featured categories (you might want to add a 'featured' flag to categories)
        $categories = Category::limit(4)->get();
        
        // Fetch featured products (you might want to add a 'featured' flag to products)
        $products = Product::with('category')->limit(8)->get();
        
        return view('home', compact('categories', 'products'));
    }
}