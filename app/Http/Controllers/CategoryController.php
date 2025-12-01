<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Spatie\SchemaOrg\Schema;

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
		$service = new \App\Services\CategoryService();

		// Apply search filter
		$search = $request->filled('search') ? $request->input('search') : null;

		if ($search) {
			$categories = $service->searchCategories($search, 100); // Get top 100 matching categories
			// Convert to paginator
			$categories = new \Illuminate\Pagination\LengthAwarePaginator(
				$categories,
				$categories->count(),
				10,
				$request->page(),
				['path' => $request->url(), 'pageName' => 'page']
			);
		} else {
			$categories = Category::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
		}

		return view('admin.categories.index', compact('categories'));
	}

	/**
	 * Store a newly created category in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse|JsonResponse
	 */
	public function store(StoreCategoryRequest $request)
	{
		$service = new \App\Services\CategoryService();

		$category = $service->createCategory($request->validated());

		if ($request->wantsJson()) {
			return response()->json([
				'success' => true,
				'message' => 'تم إضافة الفئة بنجاح!',
				'category' => $category
			]);
		}

		return redirect()->route('admin.categories.index')->with('success', 'تم إضافة الفئة بنجاح!');
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
		// Use the CacheService to get the category
		$cacheService = new \App\Services\CacheService();
		$category = $cacheService->getCategoryBySlug($categorySlug);

		// Get products for this category with optimized query
		$products = Product::where('category_id', $category->id)
			->optimized()
			->withOptimizedCategory()
			->paginate(12);

		// Generate breadcrumb structured data
		$breadcrumb = Schema::breadcrumbList()
			->itemListElement([
				Schema::listItem()
					->position(1)
					->name('الرئيسية')
					->item(url('/')),
				Schema::listItem()
					->position(2)
					->name($category->name)
					->item(url()->current())
			]);

		return view('category', compact('category', 'products', 'breadcrumb'));
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
		$service = new \App\Services\CategoryService();

		$validatedData = $request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
		]);

		$category = $service->updateCategory($category, $validatedData);

		if ($request->wantsJson()) {
			return response()->json([
				'success' => true,
				'message' => 'تم تعديل الفئة بنجاح!',
				'category' => $category
			]);
		}

		return redirect()->route('admin.categories.index')->with('success', 'تم تعديل الفئة بنجاح!');
	}

	/**
	 * Remove the specified category from storage.
	 *
	 * @param  \App\Models\Category  $category
	 * @return \Illuminate\Http\RedirectResponse|JsonResponse
	 */
	public function destroy(Category $category)
	{
		$service = new \App\Services\CategoryService();

		try {
			$deleted = $service->deleteCategory($category);
		} catch (\Exception $e) {
			$error = $e->getMessage();

			if (request()->wantsJson()) {
				return response()->json(['message' => $error], 422);
			}

			return redirect()->route('admin.categories.index')->with('error', $error);
		}

		if (request()->wantsJson()) {
			return response()->json([
				'success' => true,
				'message' => 'تم حذف الفئة بنجاح!'
			]);
		}

		return redirect()->route('admin.categories.index')->with('success', 'تم حذف الفئة بنجاح!');
	}
}
