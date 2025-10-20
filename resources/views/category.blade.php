

@section('title', $category->name)

@section('description', $category->description)

<x-layouts.app>
    <!-- Category Header -->
    <div class="hero hero-gradient text-white py-16">
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
                <select id="products-sort-select" class="select select-bordered">
                    <option value="featured">ترتيب حسب: التميز</option>
                    <option value="price_asc">السعر: من الأقل إلى الأعلى</option>
                    <option value="price_desc">السعر: من الأعلى إلى الأقل</option>
                    <option value="newest">الأحدث أولاً</option>
                </select>
                <div class="join" data-view-toggle-enabled="false" title="ملاحظة: تبديل العرض معطل حالياً - يمكن للفريق تفعيله لاحقاً">
                    <!-- <button id="view-grid-btn" class="btn join-item btn-active opacity-60 cursor-not-allowed" aria-pressed="true" title="عرض شبكة (معطل)">
                        <i data-lucide="grid-3x3"></i>
                    </button>
                    <button id="view-list-btn" class="btn join-item opacity-60 cursor-not-allowed" aria-pressed="false" title="عرض قائمة (معطل)">
                        <i data-lucide="list"></i>
                    </button> -->
                </div>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid">
                @foreach($products as $product)
                    <x-product-card 
                        id="{{ $product->id }}"
                        image="{{ $product->images ? $product->images[0] : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop&crop=center' }}"
												slug="{{ $product->slug }}"
                        title="{{ $product->title }}"
                        price="{{ $product->price }}"
                        original-price="{{ $product->price * 1.2 }}"
                        type="{{ $product->type }}" />

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
                @if(\Illuminate\Support\Facades\Route::has('home'))
                    <a href="{{ route('home') }}" class="btn btn-primary">عرض جميع المنتجات</a>
                @else
                    <a href="{{ url('/') }}" class="btn btn-primary">عرض جميع المنتجات</a>
                @endif
            </div>
        @endif
    </div>

    <!-- Contact Form -->
    <x-contact-form />
</x-layouts.app>