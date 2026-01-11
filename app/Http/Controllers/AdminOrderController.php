<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with('user');

        // Search by Order ID or User info (inside guest_info JSON or user relation)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('guest_info->name', 'like', "%{$search}%")
                  ->orWhere('guest_info->phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort by date desc
        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}
