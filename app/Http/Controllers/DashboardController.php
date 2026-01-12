<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('stock', '>', 0)->count(),
            'lowStockProducts' => Product::where('stock', '>', 0)->where('stock', '<', 10)->count(),
            'inactiveProducts' => Product::where('stock', 0)->count(),
            'newMessages' => Message::where('read', false)->count(),
            'totalMessages' => Message::count(),
			'totalVisits' => DB::table('visits')->count(),
			'todaysVisits' => DB::table('visits')->where('visited_at', now()->toDateString())->count(),
        ];

        // Fetch categories for the filter dropdown
        $categories = Category::all();

        // Build the query for products
        $query = Product::with('category');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Apply category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            switch ($request->status) {
                case 'active':
                    $query->where('stock', '>', 10);
                    break;
                case 'low-stock':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'inactive':
                    $query->where('stock', 0);
                    break;
            }
        }

        // Paginate products (5 per page)
        $products = $query->paginate(5);

        return view('dashboard', compact('stats', 'products', 'categories'));
    }

    public function searchProducts(Request $request)
    {
        $query = Product::with('category');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Apply category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            switch ($request->status) {
                case 'active':
                    $query->where('stock', '>', 10);
                    break;
                case 'low-stock':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'inactive':
                    $query->where('stock', 0);
                    break;
            }
        }

        $products = $query->paginate(5);

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'category' => $product->category ? $product->category->name : 'غير مصنف',
                    'price' => number_format($product->price, 2),
                    'stock' => $product->stock,
                    'status' => $product->stock > 10 ? 'active' : ($product->stock > 0 ? 'low-stock' : 'inactive'),
                    'image' => !empty($product->images) && is_array($product->images) ? $product->images[0] : 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=100&h=100&fit=crop',
                    'status_badge' => $product->stock > 10 ? '<span class="badge badge-success">متاح</span>' : ($product->stock > 0 ? '<span class="badge badge-warning">مخزون قليل</span>' : '<span class="badge badge-error">غير متاح</span>'),
                ];
            }),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'has_more_pages' => $products->hasMorePages(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ]
        ]);
    }

	public function visits()
	{
		$days = 30;
		$range = collect(range($days - 1, 0))->map(function ($day) {
			return now()->subDays($day)->format('Y-m-d');
		});

		$visits = DB::table('visits')
			->select('visited_at', DB::raw('count(*) as count'))
			->where('visited_at', '>=', now()->subDays($days)->toDateString())
			->groupBy('visited_at')
			->pluck('count', 'visited_at');

		// Convert dates to strings for comparison if needed, and prepare chart data
		$labels = $range->map(function ($date) {
			return \Carbon\Carbon::parse($date)->format('d-m');
		})->values();
		$data = $range->map(function ($date) use ($visits) {
			return $visits->get($date, 0);
		});

		return view('admin.visits', compact('labels', 'data'));
	}
}
