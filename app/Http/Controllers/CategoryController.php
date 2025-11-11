<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the categories.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View
	{
		$query = Category::query();

		// Apply search filter
		if ($request->filled('search')) {
			$search = $request->input('search');
			$query->where('name', 'like', "%{$search}%")
				->orWhere('description', 'like', "%{$search}%");
		}

		// Apply additional filters if needed (e.g., by date, etc.)
		if ($request->filled('date_filter')) {
			$dateFilter = $request->input('date_filter');
			switch ($dateFilter) {
				case 'today':
					$query->whereDate('created_at', today());
					break;
				case 'week':
					$query->whereDate('created_at', '>=', now()->startOfWeek());
					break;
				case 'month':
					$query->whereDate('created_at', '>=', now()->startOfMonth());
					break;
			}
		}

		$categories = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

		return view('admin.categories.index', compact('categories'));
	}

	/**
	 * Store a newly created category in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse|JsonResponse
	 */
	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
		]);

		$category = Category::create($request->only(['name', 'description']));

		if ($request->wantsJson()) {
			return response()->json([
				'success' => true,
				'message' => 'Category created successfully!',
				'category' => $category
			]);
		}

		return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
	}

	/**
	 * Display the specified category (for admin modal).
	 *
	 * @param int $id
	 * @return JsonResponse
	 */
	public function show($id): JsonResponse
	{
		$category = Category::findOrFail($id);

		return response()->json($category);
	}

	/**
	 * Show the form for editing the specified resource.
	 * For our admin modal, we return the category as JSON.
	 */
	public function edit(Category $category): JsonResponse
	{
		return response()->json($category);
	}

	/**
	 * Display products filtered by category (public route).
	 *
	 * @param  string  $categorySlug
	 * @return \Illuminate\View\View
	 */
	public function showPublic($categorySlug)
	{
		// Find the category by slug
		$category = Category::where('slug', $categorySlug)->firstOrFail();

		// Get products for this category
		$products = Product::where('category_id', $category->id)->with('category')->paginate(12);

		return view('category', compact('category', 'products'));
	}

	/**
	 * Update the specified category in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Category  $category
	 * @return \Illuminate\Http\RedirectResponse|JsonResponse
	 */
	public function update(Request $request, Category $category)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
		]);

		$category->update($request->only(['name', 'description']));

		if ($request->wantsJson()) {
			return response()->json([
				'success' => true,
				'message' => 'Category updated successfully!',
				'category' => $category
			]);
		}

		return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
	}

	/**
	 * Remove the specified category from storage.
	 *
	 * @param  \App\Models\Category  $category
	 * @return \Illuminate\Http\RedirectResponse|JsonResponse
	 */
	public function destroy(Category $category)
	{
		// Check if category has associated products
		if ($category->products()->count() > 0) {
			$error = 'لا يمكن حذف الفئة لانه يحتوي على منتجات';

			if (request()->wantsJson()) {
				return response()->json(['message' => $error], 422);
			}

			return redirect()->route('admin.categories.index')->with('error', $error);
		}

		$category->delete();

		if (request()->wantsJson()) {
			return response()->json([
				'success' => true,
				'message' => 'Category deleted successfully!'
			]);
		}

		return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
	}
}
