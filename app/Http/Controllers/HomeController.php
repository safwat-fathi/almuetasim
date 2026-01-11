<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\SettingsCacheService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with categories and products.
     *
     * @return \Illuminate\View\View
     */
    public function index(SettingsCacheService $settingsCache)
    {
        // Fetch featured categories with optimized query (only necessary columns)
        $categories = Category::optimized()->limit(4)->get();
        
        // Fetch featured products with optimized eager loading
        $products = Product::optimized()->withOptimizedCategory()->limit(8)->get();

        // Fetch settings from cache (eliminates database query on cached requests)
        $settings = $settingsCache->all();

        return view('home', compact('categories', 'products', 'settings'));
    }
}