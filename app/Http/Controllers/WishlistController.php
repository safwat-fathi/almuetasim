<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display the wishlist page.
     */
    public function index(): View
    {
        $wishlistIds = session()->get('wishlist', []);

        // Get products from wishlist IDs
        $products = Product::whereIn('id', $wishlistIds)
            ->with('category')
            ->get()
            ->sortBy(function ($product) use ($wishlistIds) {
                // Maintain the order as they were added
                return array_search($product->id, $wishlistIds);
            })
            ->values();

        return view('wishlist.index', compact('products'));
    }

    /**
     * Add a product to the wishlist.
     */
    public function add(int $productId): JsonResponse
    {
        $wishlist = session()->get('wishlist', []);

        if (! in_array($productId, $wishlist)) {
            $wishlist[] = $productId;
            session()->put('wishlist', $wishlist);
        }

        return response()->json([
            'success' => true,
            'count' => count($wishlist),
        ]);
    }

    /**
     * Remove a product from the wishlist.
     */
    public function remove(int $productId): JsonResponse
    {
        $wishlist = session()->get('wishlist', []);
        $wishlist = array_values(array_diff($wishlist, [$productId]));
        session()->put('wishlist', $wishlist);

        return response()->json([
            'success' => true,
            'count' => count($wishlist),
        ]);
    }

    /**
     * Get the count of items in the wishlist.
     */
    public function count(): JsonResponse
    {
        $count = count(session()->get('wishlist', []));

        return response()->json(['count' => $count]);
    }

    /**
     * Get top 3 products from wishlist for navbar dropdown.
     */
    public static function getTopProducts(): array
    {
        $wishlistIds = session()->get('wishlist', []);

        if (empty($wishlistIds)) {
            return [];
        }

        // Get first 3 products maintaining order
        $topIds = array_slice($wishlistIds, 0, 3);

        $products = Product::whereIn('id', $topIds)
            ->with('category')
            ->get()
            ->sortBy(function ($product) use ($topIds) {
                return array_search($product->id, $topIds);
            })
            ->values()
            ->toArray();

        return $products;
    }
}
