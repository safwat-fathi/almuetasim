@section('description', $settings['store_name'] ?? 'متجر المعتصم لفلاتر المياه')

<x-layouts.app>
    <section class="hero hero-gradient text-white min-h-[calc(45vh-65px)] relative overflow-hidden">
        <div class="bg-white/40 backdrop-blur-md flex flex-col items-center justify-center gap-4 md:gap-6 p-8 md:p-12 rounded-2xl relative z-10 text-center max-w-screen-xl mx-auto">
            {{-- تم استبدال الشعار بصورة الفلتر وجعلها في الوسط لتكون أكثر وضوحاً --}}
            <img
                src="{{ asset('images/filter-no-bg.png') }}"
                alt="فلتر مياه منزلي"
                loading="lazy"
                decoding="async"
                class="h-40 md:h-56 lg:h-80 xl:h-96 w-auto object-contain drop-shadow-2xl mx-auto select-none" />

            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white/95 drop-shadow-lg leading-tight">
                {{ $settings['store_name'] ?? 'المعتصم للفلاتر ومحطات المياه' }}
            </h1>
        </div>
    </section>

    <!-- Featured Categories -->
    <div class="container mx-auto px-4 py-13">
        <h2 class="text-4xl font-bold text-center mb-12 text-[#2d3b61]">خدماتنا المميزة</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($categories as $category)
                <x-featured-category-card slug="{{ $category->slug }}" title="{{ $category->name }}"
                    description="{{ $category->description ?? 'تصنيف منتجات مميزة' }}" />
            @endforeach
        </div>
    </div>

    <!-- Products Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <h2 class="text-4xl font-bold mb-4 md:mb-0 text-[#2d3b61]">المنتجات المميزة</h2>
            <div class="flex gap-2">
                <select id="products-sort-select" class="select select-bordered">
                    <option value="featured">ترتيب حسب: التميز</option>
                    <option value="price_asc">السعر: من الأقل إلى الأعلى</option>
                    <option value="price_desc">السعر: من الأعلى إلى الأقل</option>
                    <option value="newest">الأحدث أولاً</option>
                </select>
                <div class="join" data-view-toggle-enabled="false" title="ملاحظة: تبديل العرض معطل حالياً - يمكن للفريق تفعيله لاحقاً">
                    <!-- Toggle UI present but disabled. Set data-view-toggle-enabled="true" to enable. -->
                    <!-- <button id="view-grid-btn" class="btn join-item btn-active opacity-60 cursor-not-allowed" aria-pressed="true" title="عرض شبكة (معطل)">
                        <i data-lucide="grid-3x3"></i>
                    </button>
                    <button id="view-list-btn" class="btn join-item opacity-60 cursor-not-allowed" aria-pressed="false" title="عرض قائمة (معطل)">
                        <i data-lucide="list"></i>
                    </button> -->
                </div>
            </div>
        </div>

        <!-- Page-level search removed (now in navbar) -->

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
