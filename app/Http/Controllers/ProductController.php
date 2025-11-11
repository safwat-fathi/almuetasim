<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): View
	{
		$query = Product::with('category');
		
		// Handle search
		if ($request->has('search') && $request->search) {
			$searchTerm = $request->search;
			$query->where('title', 'LIKE', "%{$searchTerm}%");
		}
		
		// Handle category filter
		if ($request->has('category') && $request->category) {
			$query->where('category_id', $request->category);
		}
		
		// Handle status filter
		if ($request->has('status') && $request->status) {
			switch ($request->status) {
				case 'active':
					$query->where('stock', '>', 0);
					break;
				case 'inactive':
					$query->where('stock', 0);
					break;
				case 'low-stock':
					$query->where('stock', '>', 0)->where('stock', '<=', 10);
					break;
			}
		}
		
		$products = $query->orderBy('created_at', 'desc')->paginate(10);
		$categories = Category::all();

		// If the client expects JSON (AJAX), return structured JSON for client-side rendering
		if ($request->wantsJson()) {
			$products->load('category');
			return response()->json([
				'products' => $products->items(),
				'pagination' => [
					'current_page' => $products->currentPage(),
					'last_page' => $products->lastPage(),
					'per_page' => $products->perPage(),
					'total' => $products->total(),
				]
			]);
		}

		return view('admin.products.index', compact('products', 'categories'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show($productSlug)
	{
		// Find the product by slug
		$product = Product::where('slug', $productSlug)->with('category')->firstOrFail();

		// Get all related products with category information (both directions)
		$relatedProducts = $product->getAllRelatedProducts(8);

		return view('product', compact('product', 'relatedProducts'));
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
	public function store(Request $request)
	{
        $validationData = $request->all();
		
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specs' => 'nullable|json',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'is_part' => 'required|boolean',
            'warranty_months' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:product,service',
        ];
		
		// Conditionally add image validation only if files are validly uploaded
		if ($request->hasFile('images')) {
			$allValid = true;
			foreach($request->file('images') as $file) {
				if (!$file || !$file->isValid()) {
					$allValid = false;
					break;
				}
			}
			
			if ($allValid) {
				$rules['images'] = 'nullable|array|max:5';
				$rules['images.*'] = 'file|mimes:jpeg,png,jpg,webp|max:5120'; // 5MB max size per image
			} else {
				// If any file failed to upload, still validate as array but skip file-specific validation
				$rules['images'] = 'nullable|array|max:5';
			}
		} else {
			// No files sent at all
			$rules['images'] = 'nullable|array|max:5';
		}

		$validator = Validator::make($validationData, $rules);

		if ($validator->fails()) {
			if ($request->wantsJson()) {
				return response()->json([
					'message' => 'Validation failed',
					'errors' => $validator->errors()
				], 422);
			}
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$data = $request->all();
		$data['specs'] = json_decode($request->specs ?? '[]', true);
		$data['images'] = [];

		// SKU removed from schema; do not generate or accept it
		unset($data['sku']);

		// Handle file uploads if provided
		if ($request->hasFile('images')) {
			$uploadedImages = [];
			$files = $request->file('images');

			foreach ($files as $file) {
				if ($file && $file->isValid()) {
					$filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
					$path = $file->storeAs('uploads', $filename, 'public');
					$uploadedImages[] = $path;
				}
			}

			$data['images'] = $uploadedImages;
		}

        $product = Product::create($data);
		// Load relationships
		$product->load('category');

		if ($request->wantsJson()) {
			return response()->json([
				'message' => 'Product created successfully',
				'product' => $product
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
    public function update(Request $request, $id)
    {
        // Build validation rules allowing either JSON (existing image paths) or uploaded files
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specs' => 'nullable|json',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'is_part' => 'required|boolean',
            'warranty_months' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:product,service',
        ];

        if ($request->hasFile('images')) {
            $rules['images'] = 'nullable|array|max:5';
            $rules['images.*'] = 'file|mimes:jpeg,png,jpg,webp|max:5120';
        } elseif ($request->has('images')) {
            // Accept JSON for existing image paths when editing
            $rules['images'] = 'nullable|json';
        }

        $validated = $request->validate($rules);

        $product = Product::findOrFail($id);
        $data = $request->all();
        $data['specs'] = json_decode($request->specs ?? '[]', true);

        // Start with current images to avoid unintentional clearing
        $finalImages = $product->images ?? [];

        // If client submits explicit JSON list, use it as the base (keeps, ordering)
        if ($request->has('images') && !$request->hasFile('images')) {
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
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('uploads', $filename, 'public');
                    $finalImages[] = $path;
                }
            }
        }

        // Only set images if changed or provided
        if ($request->has('images') || $request->hasFile('images')) {
            $data['images'] = array_values($finalImages);
        } else {
            unset($data['images']);
        }

        // SKU removed from schema; do not generate or persist

        $product->update($data);
        $product->refresh()->load('category');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
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
		$query = Product::with('category');

		if ($request->has('search') && $request->search) {
			$searchTerm = $request->search;
			$query->where('title', 'LIKE', "%{$searchTerm}%");
		}

		if ($request->has('category') && $request->category) {
			$query->where('category_id', $request->category);
		}

		// Support a simple `sort` parameter for the public listing
		// allowed values: featured (default), price_asc, price_desc, newest
		$sort = $request->get('sort');
		switch ($sort) {
			case 'price_asc':
				$query->orderBy('price', 'asc');
				break;
			case 'price_desc':
				$query->orderBy('price', 'desc');
				break;
			case 'newest':
				$query->orderBy('created_at', 'desc');
				break;
			case 'featured':
			default:
				// No special featured flag available, fallback to newest
				$query->orderBy('created_at', 'desc');
				break;
		}

		// Use limit if specified (for navbar dropdown), otherwise paginate
		$limit = $request->get('limit');
		if ($limit) {
			$products = $query->limit($limit)->get();
		} else {
			$products = $query->paginate(20);
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
					]
				]);
			}
		}

		// If there's a search query, show search results page, otherwise show home page
		if ($request->has('search') && $request->search) {
			return view('search-results', [
				'products' => $products,
				'categories' => Category::all(),
			]);
		}

		return view('home', [
			'products' => $products,
			'categories' => Category::limit(4)->get(),
		]);
	}
	
	/**
	 * Public product listing page - shows all products with pagination
	 */
	public function indexPublic(Request $request)
	{
		$query = Product::with('category');
		
		// Handle search if provided
		if ($request->has('search') && $request->search) {
			$searchTerm = $request->search;
			$query->where('title', 'LIKE', "%{$searchTerm}%");
		}

		// Handle category filter if provided
		if ($request->has('category') && $request->category) {
			$query->where('category_id', $request->category);
		}

		// Handle sorting
		$sort = $request->get('sort');
		switch ($sort) {
			case 'price_asc':
				$query->orderBy('price', 'asc');
				break;
			case 'price_desc':
				$query->orderBy('price', 'desc');
				break;
			case 'newest':
				$query->orderBy('created_at', 'desc');
				break;
			case 'featured':
			default:
				// No special featured flag available, fallback to newest
				$query->orderBy('created_at', 'desc');
				break;
		}

		$products = $query->paginate(12); // 12 products per page for public listing

		return view('products.public-listing', [
			'products' => $products,
			'categories' => Category::all(),
			'currentCategory' => $request->get('category'),
			'currentSearch' => $request->get('search'),
			'currentSort' => $request->get('sort'),
		]);
	}
}
