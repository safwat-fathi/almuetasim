<x-layouts.app title="إتمام الشراء">
    <div class="min-h-screen bg-base-200 py-8">
        <div class="container mx-auto px-4 max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary mb-2">إتمام الشراء</h1>
                <p class="text-gray-600">أدخل بياناتك لإكمال الطلب</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Order Summary -->
                <div class="md:col-span-1 order-2 md:order-1">
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body p-4">
                            <h3 class="font-bold text-lg mb-4">ملخص الطلب</h3>
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @foreach($cart as $item)
                                    <div class="flex gap-4 border-b pb-4 last:border-0 last:pb-0">
                                        <div class="w-16 h-16 flex-shrink-0">
                                            <img src="{{ isset($item['image']) ? asset('storage/' . $item['image']) : asset('storage/uploads/default-product.jpg') }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="w-full h-full object-cover rounded">
                                        </div>
                                        <div class="flex-grow">
                                            <p class="text-sm font-semibold">{{ $item['name'] }}</p>
                                            <div class="flex justify-between items-center mt-1">
                                                <span class="text-xs text-gray-500">الكمية: {{ $item['quantity'] }}</span>
                                                <span class="text-sm font-bold text-primary">
                                                    {{ number_format($item['price'] * $item['quantity'], 2) }} ج.م
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="divider my-2"></div>
                            
                            <div class="flex justify-between font-bold text-lg">
                                <span>الإجمالي</span>
                                <span class="text-primary">{{ number_format($total, 2) }} ج.م</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="md:col-span-2 order-1 md:order-2">
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title mb-4">بيانات التوصيل</h2>
                            
                            @if(session('error'))
                                <div class="alert alert-error mb-4">
                                    <span>{{ session('error') }}</span>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-error mb-4">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('checkout.store') }}" method="POST">
                                @csrf
                                
                                <div class="form-control w-full mb-4">
                                    <label class="label">
                                        <span class="label-text">الاسم الكامل <span class="text-error">*</span></span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered w-full" required />
                                </div>

                                <div class="form-control w-full mb-4">
                                    <label class="label">
                                        <span class="label-text">رقم الهاتف <span class="text-error">*</span></span>
                                    </label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" class="input input-bordered w-full" required />
                                </div>

                                <div class="form-control flex flex-col w-full mb-4">
                                    <label class="label">
                                        <span class="label-text">العنوان <span class="text-error">*</span></span>
                                    </label>
                                    <textarea name="address" class="w-full textarea textarea-bordered h-24" required>{{ old('address') }}</textarea>
                                </div>

                                <div class="form-control flex flex-col w-full mb-4">
                                    <label class="label">
                                        <span class="label-text">ملاحظات إضافية (اختياري)</span>
                                    </label>
                                    <textarea name="notes" class="w-full textarea textarea-bordered">{{ old('notes') }}</textarea>
                                </div>

                                <div class="form-control w-full mb-6">
                                    <label class="label cursor-pointer justify-start gap-4">
                                        <input type="radio" name="payment_method" value="COD" checked onclick="return false;" class="radio radio-primary" />
                                        <span class="label-text font-semibold">الدفع عند الاستلام (COD)</span>
                                    </label>
                                    <p class="text-sm text-gray-500 mr-8">الدفع نقداً عند استلام الطلب فقط.</p>
                                </div>

                                <div class="card-actions justify-end mt-6">
                                    <button type="submit" class="btn btn-primary btn-block">تأكيد الطلب</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
