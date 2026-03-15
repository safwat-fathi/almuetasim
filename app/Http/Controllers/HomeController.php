<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Product;
use App\Services\SettingsCacheService;

class HomeController extends Controller
{
    /**
     * Display the home page with categories and products.
     *
     * @return \Illuminate\View\View
     */
    public function index(SettingsCacheService $settingsCache)
    {
        $categories = Category::optimized()->limit(4)->get();
        $products = Product::optimized()->withOptimizedCategory()->limit(8)->get();
        $galleryItems = GalleryItem::query()->latest()->limit(4)->get();
        $settings = $settingsCache->all();

        return view('home', compact('categories', 'products', 'galleryItems', 'settings'));
    }
}
