{{-- @extends('layouts.app') --}}

@section('description', 'متجر المعتصم لفلاتر المياه')

{{-- @section('content') --}}
<x-layouts.app>
    <section class="hero bg-gradient-to-r from-primary to-secondary text-white min-h-[calc(100vh-65px)]">
        <div class="bg-white/40 flex flex-col items-center justify-center gap-4 p-20 rounded-2xl">
            <img src="{{ asset('images/ALMUETASIM-300x212.png') }}" alt="المعتصم لفلاتر المياه" loading="lazy"
                class="drop-shadow-lg">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg w-3/4 text-center">مرحبا بكم في متجر المعتصم لفلاتر
                المياه</h1>
        </div>
    </section>

    <!-- Featured Categories -->
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12">خدماتنا المميزة</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($categories as $category)
                <x-featured-category-card icon="box" slug="{{ $category->slug }}" title="{{ $category->name }}"
                    description="{{ $category->description ?? 'تصنيف منتجات مميزة' }}" />
            @endforeach
        </div>
    </div>

    <!-- Products Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <h2 class="text-3xl font-bold mb-4 md:mb-0">المنتجات المميزة</h2>
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid">
            <!-- Sample Products -->
            @foreach ($products as $product)
                <x-product-card id="{{ $product->id }}"
                    image="{{ $product->images ? $product->images[0] : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop&crop=center' }}"
                    slug="{{ $product->slug }}" title="{{ $product->title }}" price="{{ $product->price }}"
                    type="{{ $product->type }}"
                    original-price="{{ $product->price * 1.2 }}" on-sale="true" />
            @endforeach
        </div>
    </div>

    <!-- Contact Form -->
    <x-contact-form />
</x-layouts.app>
{{-- @endsection --}}
