<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Spatie\SchemaOrg\Schema;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $service = new \App\Services\ProductService();

        $filters = [
            'search' => $request->get('search'),
            'category' => $request->get('category'),
            'status' => $request->get('status'),
        ];

        $products = $service->getProducts($filters, 10);
        $categories = (new \App\Services\CacheService())->getAllCategories();

        // If the client expects JSON (AJAX), return structured JSON for client-side rendering
        if ($request->wantsJson()) {
            return response()->json([
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);
        }

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show($productSlug)
    {
        // Use the CacheService to get the product
        $cacheService = new \App\Services\CacheService();
        $product = $cacheService->getProductBySlug($productSlug);

        // Get all related products with category information (both directions)
        $relatedProducts = $product->getAllRelatedProducts(8);

        // Check if product is in wishlist
        $wishlist = session()->get('wishlist', []);
        $inWishlist = in_array($product->id, $wishlist);

        // Generate Schema.org structured data
        $schema = Schema::product()
            ->name($product->title)
            ->description($product->description)
            ->sku($product->id) // Using ID as SKU since SKU was removed
            ->category($product->category->name)
            ->brand(Schema::organization()->name('المعتصم للفلاتر ومحطات المياه'))
            ->offers(Schema::offer()
                ->price($product->price)
                ->priceCurrency('SAR')
                ->availability($product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock')
                ->seller(Schema::organization()->name('المعتصم للفلاتر ومحطات المياه'))
            );

        // Add images if available
        if (!empty($product->images)) {
            $imageUrls = [];
            foreach ($product->images as $image) {
                if (Str::startsWith($image, ['http://', 'https://'])) {
                    $imageUrls[] = $image;
                } else {
                    $imageUrls[] = asset('storage/' . $image);
                }
            }
            $schema->image($imageUrls);
        }

        // Add aggregateRating if reviews exist (placeholder for now)
        // $schema->aggregateRating(Schema::aggregateRating()
        //     ->ratingValue(4.8)
        //     ->reviewCount(247)
        // );

        return view('product', compact('product', 'relatedProducts', 'inWishlist', 'schema'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    // 	$categories = Category::all();
    // 	return view('admin.products.create', compact('categories'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\StoreProductRequest $request)
    {
        $service = new \App\Services\ProductService();
        $imageService = new ImageOptimizationService;

        $data = $request->validated();
        $data['images'] = [];

        // Handle file uploads if provided
        if ($request->hasFile('images')) {
            $uploadedImages = [];
            $files = $request->file('images');

            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    try {
                        // Convert to WebP and optimize
                        $path = $imageService->optimizeAndConvertToWebP($file, 'uploads');
                        $uploadedImages[] = $path;
                    } catch (\Exception $e) {
                        // Fallback to original upload if conversion fails
                        $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();
                        $path = $file->storeAs('uploads', $filename, 'public');
                        $uploadedImages[] = $path;
                    }
                }
            }

            $data['images'] = $uploadedImages;
        }

        $product = $service->createProduct($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
            ], 201);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    // 	$product = Product::findOrFail($id);
    // 	$categories = Category::all();
    // 	return view('admin.products.edit', compact('product', 'categories'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\UpdateProductRequest $request, $id)
    {
        $service = new \App\Services\ProductService();
        $imageService = new ImageOptimizationService;

        $product = Product::findOrFail($id);
        $data = $request->validated();

        // Start with current images to avoid unintentional clearing
        $finalImages = $product->images ?? [];

        // If client submits explicit JSON list, use it as the base (keeps, ordering)
        if ($request->has('images') && ! $request->hasFile('images')) {
            $fromJson = json_decode($request->images ?? '[]', true) ?: [];
            if (is_array($fromJson)) {
                $finalImages = $fromJson;
            }
        }

        // Handle new uploads and merge with the base list
        if ($request->hasFile('images')) {
            $files = $request->file('images');

            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    try {
                        // Convert to WebP and optimize
                        $path = $imageService->optimizeAndConvertToWebP($file, 'uploads');
                        $finalImages[] = $path;
                    } catch (\Exception $e) {
                        // Fallback to original upload if conversion fails
                        $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();
                        $path = $file->storeAs('uploads', $filename, 'public');
                        $finalImages[] = $path;
                    }
                }
            }
        }

        // Only set images if changed or provided
        if ($request->has('images') || $request->hasFile('images')) {
            $data['images'] = array_values($finalImages);
        } else {
            unset($data['images']);
        }

        $product = $service->updateProduct($product, $data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Product deleted successfully']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Search products (legacy method)
     */
    public function search(Request $request)
    {
        // Redirect to index with parameters for consistency
        return redirect()->route('admin.products.index', [
            'search' => $request->input('q'),
        ]);
    }

    /**
     * Public products endpoint for AJAX on the homepage and search results page.
     */
    public function publicIndex(Request $request)
    {
        $service = new \App\Services\ProductService();

        $filters = [
            'search' => $request->get('search'),
            'category' => $request->get('category'),
            'sort' => $request->get('sort'),
        ];

        // Use limit if specified (for navbar dropdown), otherwise paginate
        $limit = $request->get('limit');
        if ($limit) {
            $products = $service->searchProducts($filters['search'] ?? '', $limit);
        } else {
            $products = $service->getProducts($filters, 20);
        }

        if ($request->wantsJson()) {
            if ($limit) {
                return response()->json(['products' => $products]);
            } else {
                return response()->json([
                    'products' => $products->items(),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                    ],
                ]);
            }
        }

        // If there's a search query, show search results page, otherwise show home page
        if ($request->has('search') && $request->search) {
            $categories = (new \App\Services\CacheService())->getAllCategories();
            return view('search-results', [
                'products' => $products,
                'categories' => $categories,
            ]);
        }

        $categories = (new \App\Services\CacheService())->getAllCategories()->take(4);
        return view('home', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Public product listing page - shows all products with pagination
     */
    public function indexPublic(Request $request)
    {
        $service = new \App\Services\ProductService();

        $filters = [
            'search' => $request->get('search'),
            'category' => $request->get('category'),
            'sort' => $request->get('sort'),
        ];

        $products = $service->getProducts($filters, 12); // 12 products per page for public listing

        $categories = (new \App\Services\CacheService())->getAllCategories();

        return view('products.public-listing', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $request->get('category'),
            'currentSearch' => $request->get('search'),
            'currentSort' => $request->get('sort'),
        ]);
    }
}
