<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Product; // Assuming you have a Product model

class CartController extends Controller
{
    /**
     * Add a product to the cart
     */
    public function add($productId): JsonResponse
    {
        try {
            $product = Product::findOrFail($productId);
            
            $cart = session()->get('cart', []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity']++;
            } else {
                $cart[$productId] = [
                    'id' => $product->id,
                    'name' => $product->title,
                    'price' => $product->price,
                    'image' => !empty($product->images) ? $product->images[0] : null, // product has images as json array, get first one
                    'quantity' => 1,
                    'slug' => $product->slug
                ];
            }
            
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'count' => $this->getCartCount()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding product to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a product from the cart
     */
    public function remove($productId): JsonResponse
    {
        try {
            $cart = session()->get('cart', []);
            
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart',
                'count' => $this->getCartCount()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing product from cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product quantity in the cart
     */
    public function update($productId, Request $request): JsonResponse
    {
        try {
            $cart = session()->get('cart', []);
            
            $quantity = $request->input('quantity', 1);
            
            if (isset($cart[$productId]) && $quantity > 0) {
                $cart[$productId]['quantity'] = $quantity;
                session()->put('cart', $cart);
            } elseif (isset($cart[$productId]) && $quantity <= 0) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'count' => $this->getCartCount(),
				'items' => $this->getCartItems(),
				'formatted_total' => $this->formatMoney($this->getCartTotal())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the total count of items in the cart
     */
    public function count(): JsonResponse
    {
        $count = $this->getCartCount();
        
        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Get all cart items
     */
    public function items(): JsonResponse
    {
        $cart = session()->get('cart', []);
        
        return response()->json([
			'items' => $this->getCartItems(),
            'count' => $this->getCartCount(),
			'total' => $this->getCartTotal(),
			'formatted_total' => $this->formatMoney($this->getCartTotal())
        ]);
    }

    /**
     * Clear the entire cart
     */
    public function clear(): JsonResponse
    {
        try {
            session()->forget('cart');
            
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared',
                'count' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the cart page
     */
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = $this->getCartTotal();
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Helper method to get cart count
     */
    private function getCartCount(): int
    {
        $cart = session()->get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }

    /**
     * Helper method to get cart total
     */
    private function getCartTotal(): float
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }

    /**
     * Helper method to get cart items
     */
    private function getCartItems(): array
    {
        $cart = session()->get('cart', []);

		return array_map(function ($item) {
			$item['formatted_price'] = $this->formatMoney($item['price']);
			$item['formatted_total_price'] = $this->formatMoney($item['price'] * $item['quantity']);
			return $item;
		}, array_values($cart));
	}

	/**
	 * Format money consistently with @money directive
	 */
	private function formatMoney($amount): string
	{
		$amount = (float) $amount;
		if (class_exists('NumberFormatter')) {
			$fmt = new \NumberFormatter('ar_EG', \NumberFormatter::CURRENCY);
			return $fmt->formatCurrency($amount, 'EGP');
		}

		return number_format($amount, 2) . ' ج.م';
    }
}