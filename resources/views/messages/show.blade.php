

@section('title', 'عرض الرسالة')

<x-layouts.admin>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">عرض الرسالة</h1>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline">
            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
            العودة للرسائل
        </a>
    </div>

    <div class="bg-base-100 rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold text-lg mb-2">معلومات المرسل</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">الاسم:</span> {{ $message->name }}</p>
                    <p><span class="font-medium">البريد الإلكتروني:</span> {{ $message->email }}</p>
                    <p><span class="font-medium">رقم الهاتف:</span> {{ $message->phone ?? 'غير متوفر' }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="font-semibold text-lg mb-2">تفاصيل الرسالة</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">تاريخ الإرسال:</span> {{ $message->created_at->format('Y-m-d H:i') }}</p>
                    <p><span class="font-medium">آخر تحديث:</span> {{ $message->updated_at->format('Y-m-d H:i') }}</p>
                    <p>
                        <span class="font-medium">الحالة:</span>
                        @if($message->read)
                            <span class="badge badge-success">مقروءة</span>
                        @else
                            <span class="badge badge-warning">جديدة</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div>
            <h3 class="font-semibold text-lg mb-2">محتوى الرسالة</h3>
            <div class="bg-base-200 p-4 rounded-lg">
                <p class="whitespace-pre-wrap">{{ $message->message }}</p>
            </div>
        </div>
    </div>
    
    <div class="flex gap-2">
        <a href="mailto:{{ $message->email }}" class="btn btn-primary">
            <i data-lucide="mail" class="w-4 h-4 ml-2"></i>
            الرد على البريد
        </a>
        
        @if(!$message->read)
            <form method="POST" action="{{ route('admin.messages.markAsRead', $message) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">
                    <i data-lucide="check" class="w-4 h-4 ml-2"></i>
                    تعليم كمقروءة
                </button>
            </form>
        @endif
    </div>
</div>
</x-layouts.admin>