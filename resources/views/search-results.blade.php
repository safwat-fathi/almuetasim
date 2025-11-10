@section('description', 'نتائج البحث - ' . (request('search') ? request('search') : 'المعتصم لفلاتر المياه'))

<x-layouts.app>
    <section class="hero hero-gradient text-white min-h-[calc(20vh-65px)]">
        <div class="bg-white/40 flex flex-col items-center justify-center gap-4 p-10 rounded-2xl">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg text-center flex items-center justify-center gap-4">
               
                <i data-lucide="search" class="w-10 h-10"></i>
                نتائج البحث
              
            </h1>
            @if(request('search'))
                <p class="text-lg text-white/90">عرض النتائج لـ: "{{ request('search') }}"</p>
            @endif
        </div>
    </section>

    <!-- Search and Filters Section -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-base-100 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="form-control flex-1 max-w-md">
                    <div class="input-group">
                        <input type="text" id="search-input" placeholder="ابحث عن منتج..." class="input input-bordered flex-1"
                               value="{{ request('search') }}" />
                       
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <select id="sort-select" class="select select-bordered" onchange="performSearch()">
                        <option value="featured" {{ request('sort') == 'featured' || !request('sort') ? 'selected' : '' }}>ترتيب حسب: التميز</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: من الأقل إلى الأعلى</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى إلى الأقل</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث أولاً</option>
                    </select>
                    <select id="category-filter" class="select select-bordered" onchange="performSearch()">
                        <option value="">كل الفئات</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Results Count -->
        <div class="mb-6">
            <p class="text-lg text-base-content/70">
                @if($products->count() > 0)
                    تم العثور على {{ $products->count() }} منتج{{ $products->count() > 1 ? '' : '' }}
                @else
                    لم يتم العثور على منتجات مطابقة لبحثك
                @endif
            </p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid">
            @forelse ($products as $product)
                <x-product-card id="{{ $product->id }}"
                    image="{{ $product->images ? $product->images[0] : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop&crop=center' }}"
                    slug="{{ $product->slug }}" title="{{ $product->title }}" price="{{ $product->price }}"
                    type="{{ $product->type }}"
                    original-price="{{ $product->price * 1.2 }}" on-sale="true" />
            @empty
                <div class="col-span-full text-center py-16">
                    <i data-lucide="search-x" class="w-16 h-16 text-base-content/30 mx-auto mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">لا توجد نتائج</h3>
                    <p class="text-base-content/60 mb-4">جرب كلمات بحث مختلفة أو تصفح فئاتنا</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">العودة للصفحة الرئيسية</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</x-layouts.app>

<x-slot:scripts>
    <script>
        function performSearch() {
            const searchTerm = document.getElementById('search-input').value;
            const sort = document.getElementById('sort-select').value;
            const category = document.getElementById('category-filter').value;

            const params = new URLSearchParams();
            if (searchTerm) params.set('search', searchTerm);
            if (sort && sort !== 'featured') params.set('sort', sort);
            if (category) params.set('category', category);

            window.location = '/products?' + params.toString();
        }

        // Handle Enter key in search input
        document.getElementById('search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    </script>
</x-slot:scripts>
