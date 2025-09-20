@extends('layouts.app')

@section('title', $category->name)

@section('description', $category->description)

@section('content')
    <!-- Category Header -->
    <div class="bg-gradient-to-r from-primary to-secondary text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ $category->name }}</h1>
            <p class="text-xl opacity-90">{{ $category->description }}</p>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <h2 class="text-3xl font-bold mb-4 md:mb-0">المنتجات</h2>
            <div class="flex gap-2">
                <select class="select select-bordered">
                    <option>ترتيب حسب: التميز</option>
                    <option>السعر: من الأقل إلى الأعلى</option>
                    <option>السعر: من الأعلى إلى الأقل</option>
                    <option>الأحدث أولاً</option>
                </select>
                <div class="join">
                    <button class="btn join-item btn-active">
                        <i data-lucide="grid-3x3"></i>
                    </button>
                    <button class="btn join-item">
                        <i data-lucide="list"></i>
                    </button>
                </div>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid">
                @foreach($products as $product)
                    <x-product-card 
                        id="{{ $product->id }}"
                        image="{{ $product->images ? $product->images[0] : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop&crop=center' }}"
                        title="{{ $product->title }}"
                        price="{{ $product->price }}"
                        original-price="{{ $product->price * 1.2 }}"
                        on-sale="true" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <h3 class="text-2xl font-bold mb-4">لا توجد منتجات في هذا التصنيف</h3>
                <p class="text-base-content/70 mb-8">يرجى التحقق من التصنيفات الأخرى</p>
                <a href="{{ route('home') }}" class="btn btn-primary">عرض جميع المنتجات</a>
            </div>
        @endif
    </div>

    <!-- Newsletter -->
    <div class="bg-base-200 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">ابقوا على اطلاع</h2>
            <p class="text-lg mb-8">
                اشتركوا في نشرتنا البريدية للحصول على أحدث العروض والمنتجات الجديدة
            </p>
            <div class="max-w-md mx-auto">
                <div class="join w-full">
                    <input class="input input-bordered join-item w-full" placeholder="أدخل بريدك الإلكتروني" />
                    <button class="btn btn-primary join-item">اشتراك</button>
                </div>
            </div>
        </div>
    </div>
@endsection