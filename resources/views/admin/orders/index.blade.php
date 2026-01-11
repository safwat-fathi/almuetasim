<x-layouts.admin title="Orders Management">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">إدارة الطلبات</h1>

        <!-- Filters -->
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label">ابحث (رقم الطلب / اسم العميل)</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="input input-bordered w-full" placeholder="بحث...">
                    </div>
                    <div class="form-control">
                        <label class="label">الحالة</label>
                        <select name="status" class="select select-bordered w-full">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>جاري التجهيز</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <div class="form-control mt-9">
                        <button type="submit" class="btn btn-primary">تصفية</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg shadow">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>التاريخ</th>
                        <th>المجموع</th>
                        <th>طريقة الدفع</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="hover">
                            <td>#{{ $order->id }}</td>
                            <td>
                                <div>{{ $order->guest_info['name'] ?? $order->user->name ?? 'زائر' }}</div>
                                <div class="text-xs opacity-50">{{ $order->guest_info['phone'] ?? $order->user->phone ?? '-' }}</div>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="font-bold">@money($order->total_amount)</td>
                            <td>{{ $order->payment_method }}</td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'pending' => 'badge-warning',
                                        'processing' => 'badge-info',
                                        'shipped' => 'badge-primary',
                                        'delivered' => 'badge-success',
                                        'cancelled' => 'badge-error',
                                        default => 'badge-ghost',
                                    };
                                    $statusLabel = match($order->status) {
                                        'pending' => 'قيد الانتظار',
                                        'processing' => 'جاري التجهيز',
                                        'shipped' => 'تم الشحن',
                                        'delivered' => 'تم التسليم',
                                        'cancelled' => 'ملغي',
                                        default => $order->status,
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-ghost">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-base-content/60">لا توجد طلبات.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</x-layouts.admin>
