<x-layouts.app title="تم الطلب بنجاح">
    <div class="min-h-screen bg-base-200 py-12">
        <div class="container mx-auto px-4 max-w-2xl">
            <div class="card bg-base-100 shadow-xl text-center">
                <div class="card-body items-center">
                    <div class="w-20 h-20 bg-success/20 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="check" class="w-10 h-10 text-success"></i>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-success mb-2">تم استلام طلبك بنجاح!</h1>
                    <p class="text-gray-600 mb-8">شكراً للشراء، رقم طلبك هو #{{ $order->id }}</p>
                    
                    <div class="w-full bg-base-200 rounded-lg p-6 mb-8 text-right">
                        <h3 class="font-bold mb-4 border-b pb-2">تفاصيل الطلب</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">الاسم:</span>
                                <span class="font-semibold">{{ $order->guest_info['name'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">رقم الهاتف:</span>
                                <span class="font-semibold">{{ $order->guest_info['phone'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">العنوان:</span>
                                <span class="font-semibold">{{ $order->shipping_address }}</span>
                            </div>
                            <div class="divider"></div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">طريقة الدفع:</span>
                                <span class="font-semibold">الدفع عند الاستلام</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg text-primary mt-2">
                                <span>الإجمالي:</span>
                                <span>{{ number_format($order->total_amount, 2) }} ج.م</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a href="{{ route('home') }}" class="btn btn-primary">العودة للرئيسية</a>
                        <a href="{{ route('products.public.list') }}" class="btn btn-outline">متابعة التسوق</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
