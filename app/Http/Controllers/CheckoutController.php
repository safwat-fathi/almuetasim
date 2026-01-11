<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^01[0125][0-9]{8}$/'],
            'address' => 'required|string',
            'notes' => 'nullable|string',
        ], [
            'phone.regex' => 'رقم الهاتف غير صحيح. يجب أن يكون رقم مصري مكون من 11 رقم ويبدأ بـ 01',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => auth()->id(), // null if guest
                'guest_info' => [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'notes' => $request->notes,
                ],
                'shipping_address' => $request->address,
                'total_amount' => $total,
                'payment_method' => 'COD',
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            session()->forget('cart');

            DB::commit();

            return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }
}
