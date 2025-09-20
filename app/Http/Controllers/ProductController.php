<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($productSlug)
    {
        // Find the product by slug
        $product = Product::where('slug', $productSlug)->with('category')->firstOrFail();
        
        return view('product', compact('product'));
    }
}
