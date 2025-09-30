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
	public function index(): View
	{
		$products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);
		$categories = Category::all();
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
		$validator = Validator::make($request->all(), [
			'sku' => 'required|string|max:255|unique:products',
			'title' => 'required|string|max:255',
			'description' => 'required|string',
			'specs' => 'nullable|json',
			'price' => 'required|numeric|min:0',
			'stock' => 'required|integer|min:0',
			'is_part' => 'required|boolean',
			'warranty_months' => 'required|integer|min:0',
			'images' => 'nullable|array|max:5', // Up to 5 images
			'images.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max size per image
			'category_id' => 'nullable|exists:categories,id',
			'type' => 'required|in:product,service',
		]);

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

		// Handle file uploads if provided
		if ($request->hasFile('images')) {
			$uploadedImages = [];
			$files = $request->file('images');

			foreach ($files as $file) {
				if ($file) {
					$filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
					$path = $file->storeAs('uploads', $filename, 'public');
					$uploadedImages[] = $path;
				}
			}

			$data['images'] = $uploadedImages;
		}

		$product = Product::create($data);

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
		$request->validate([
			'sku' => 'required|string|max:255|unique:products,sku,' . $id,
			'title' => 'required|string|max:255',
			'description' => 'required|string',
			'specs' => 'nullable|json',
			'price' => 'required|numeric|min:0',
			'stock' => 'required|integer|min:0',
			'is_part' => 'required|boolean',
			'warranty_months' => 'required|integer|min:0',
			'images' => 'nullable|json',
			'category_id' => 'nullable|exists:categories,id',
			'type' => 'required|in:product,service',
		]);

		$product = Product::findOrFail($id);
		$data = $request->all();
		$data['specs'] = json_decode($request->specs ?? '[]', true);
		$data['images'] = json_decode($request->images ?? '[]', true);

		$product->update($data);

		return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$product = Product::findOrFail($id);
		$product->delete();

		return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
	}

	/**
	 * Search products
	 */
	public function search(Request $request)
	{
		$query = $request->input('q');
		$products = Product::where('title', 'LIKE', "%{$query}%")
			->orWhere('sku', 'LIKE', "%{$query}%")
			->with('category')
			->paginate(10);

		return view('admin.products.index', compact('products'));
	}
}
