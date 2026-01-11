<x-layouts.admin title="Order Details">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">تفاصيل الطلب #{{ $order->id }}</h1>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">عودة للقائمة</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items & Status -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Card -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4">تحديث الحالة</h2>
                        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                            @csrf
                            @method('PATCH')
                            
                            <div class="form-control w-full sm:w-auto flex-1">
                                <label class="label">حالة الطلب</label>
                                <select name="status" class="select select-bordered w-full">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>جاري التجهيز</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                </select>
                            </div>

                            <div class="form-control w-full sm:w-auto flex-1">
                                <label class="label">حالة الدفع</label>
                                <select name="payment_status" class="select select-bordered w-full">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>معلق</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>مدفوع</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>فشل</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">تحديث</button>
                        </form>
                        @if(session('success'))
                            <div class="alert alert-success mt-4">
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items Table -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body p-0 sm:p-6">
                        <h2 class="card-title text-lg mb-4 px-6 sm:px-0 pt-6 sm:pt-0">المنتجات</h2>
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>المنتج</th>
                                        <th>السعر</th>
                                        <th>الكمية</th>
                                        <th>الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    @if($item->product && $item->product->images && count($item->product->images) > 0)
                                                        <div class="avatar">
                                                            <div class="mask mask-squircle w-12 h-12">
                                                                <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product_name }}" />
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-bold">{{ $item->product_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>@money($item->price)</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="font-bold">@money($item->total)</td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-base-200 font-bold text-lg">
                                        <td colspan="3" class="text-left">الإجمالي النهائي:</td>
                                        <td>@money($order->total_amount)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Info -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4">معلومات العميل</h2>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="avatar placeholder">
                                <div class="bg-neutral-focus text-neutral-content rounded-full w-12">
                                    <span class="text-xl">{{ substr($order->guest_info['name'] ?? $order->user->name ?? '?', 0, 1) }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">{{ $order->guest_info['name'] ?? $order->user->name ?? 'Guest' }}</div>
                                <div class="text-sm opacity-50">عميل</div>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex gap-2">
                                <i data-lucide="phone" class="w-4 h-4 opacity-70"></i>
                                <span>{{ $order->guest_info['phone'] ?? $order->user->phone ?? 'غير متوفر' }}</span>
                            </div>
                            @if(isset($order->guest_info['notes']) && $order->guest_info['notes'])
                                <div class="mt-2 pt-2 border-t">
                                    <span class="font-bold block mb-1">ملاحظات:</span>
                                    <p class="text-gray-600">{{ $order->guest_info['notes'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4">عنوان التوصيل</h2>
                        <div class="p-4 bg-base-200 rounded-lg">
                            <p class="whitespace-pre-wrap leading-relaxed">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Logistics -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4">معلومات إضافية</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="opacity-70">طريقة الدفع</span>
                                <span class="badge badge-outline">{{ $order->payment_method }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="opacity-70">تاريخ الطلب</span>
                                <span class="text-sm">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
