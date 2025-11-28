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
            'dropdownHtml' => self::getDropdownHtml(),
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
            'dropdownHtml' => self::getDropdownHtml(),
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

    /**
     * Get the HTML for the wishlist dropdown.
     */
    public static function getDropdownHtml(): string
    {
        $products = self::getTopProducts();
        $html = '';

        if (empty($products)) {
            return '<p class="text-base-content/70 text-sm">المفضلة فارغة</p>';
        }

        foreach ($products as $productData) {
            // Convert array to object for the view component if needed, or pass as array
            // The component expects a $product object or array. Let's check how it's used.
            // In navbar it uses $product['price'] etc because toArray() was called.
            // But the component <x-product.wishlist-inline> might expect an object if it uses -> access.
            // Let's look at the component code.
            
            // We need to reconstruct the product object or pass data that the component accepts.
            // Since we are inside the controller, we can just render the view partial manually.
            
            // However, blade components are tricky to render manually outside of a view.
            // A simpler approach is to use a blade view fragment or just construct the HTML string if it's simple.
            // Or better, return the view of the dropdown content.
            
            // Let's try to render the component.
            // Note: $productData is an array here because getTopProducts returns toArray().
            // We should probably modify getTopProducts to return objects or handle it here.
            
            // Actually, let's just fetch the objects again or remove toArray() from getTopProducts if possible, 
            // OR just hydrate them.
            
            $product = new Product($productData);
            $product->id = $productData['id']; // Ensure ID is set
            // We might need to handle images manually as accessors might not work on array-hydrated models without casting.
            
            // Let's just use the view() helper to render a partial.
            // We can create a partial for the dropdown items if it doesn't exist, or use the component.
            
            // Let's check the component file content first.
        }
        
        // Actually, the cleanest way is to return a view that loops over the products.
        // Let's create a temporary view or use the existing logic.
        
        // Let's change getTopProducts to NOT return toArray() so we have models.
        // But wait, getTopProducts is static and might be used elsewhere.
        // It is used in navbar.blade.php: $wishlistTopProducts = WishlistController::getTopProducts();
        // In navbar.blade.php lines 203-221, it iterates and accesses as array $product['price'].
        // So changing it would break navbar initial load.
        
        // We will keep getTopProducts as is.
        // We will implement getDropdownHtml by re-fetching or using the array.
        
        // Let's use the view rendering.
        return view('components.layouts.partials.wishlist-dropdown-items', ['products' => $products])->render();
    }
}

