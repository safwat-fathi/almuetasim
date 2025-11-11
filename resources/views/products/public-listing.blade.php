<x-layouts.app title="جميع المنتجات">
    <div class="min-h-screen bg-base-200 py-8">
        <div class="container mx-auto px-4">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-primary mb-4">جميع المنتجات</h1>
                <p class="text-lg text-gray-600">تصفح جميع منتجاتنا المتوفرة</p>
            </div>

            <!-- Search and Filter Section -->
            <div class="card bg-base-100 shadow-xl mb-8">
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search Form -->
                        <div class="form-control">
                            <form method="GET" action="{{ route('products.public.list') }}">
                                <div class="input-group">
                                    <input type="text" 
                                           name="search" 
                                           placeholder="البحث عن منتج..." 
                                           value="{{ request('search') }}"
                                           class="input input-bordered w-full" />
                                    
                                </div>
                            </form>
                        </div>

                        <!-- Category Filter -->
                        <div class="form-control">
                            <form method="GET" action="{{ route('products.public.list') }}">
                                <div class="input-group">
                                    <select name="category" class="select select-bordered w-full" onchange="this.form.submit()">
                                        <option value="">جميع الفئات</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(request('search'))
                                        <input type="hidden" name="search" value="{{ request('search') }}" />
                                    @endif
                                </div>
                            </form>
                        </div>

                        <!-- Sort Options -->
                        <div class="form-control">
                            <form method="GET" action="{{ route('products.public.list') }}">
                                <div class="input-group">
                                    <select name="sort" class="select select-bordered w-full" onchange="this.form.submit()">
                                        <option value="" {{ !request('sort') || request('sort') == 'featured' ? 'selected' : '' }}>الأكثر تقييماً</option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>الأقل سعراً</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>الأعلى سعراً</option>
                                    </select>
                                    @if(request('search'))
                                        <input type="hidden" name="search" value="{{ request('search') }}" />
                                    @endif
                                    @if(request('category'))
                                        <input type="hidden" name="category" value="{{ request('category') }}" />
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($products as $product)
                        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                            <figure class="relative">
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                         alt="{{ $product->title }}" 
                                         class="w-full h-48 object-cover" />
                                @else
                                    <img src="{{ asset('storage/uploads/default-product.jpg') }}" 
                                         alt="{{ $product->title }}" 
                                         class="w-full h-48 object-cover" />
                                @endif
                                @if($product->is_part)
                                    <div class="absolute top-2 right-2 badge badge-warning badge-outline"> spare part </div>
                                @endif
                            </figure>
                            <div class="card-body">
                                <h2 class="card-title text-lg">{{ $product->title }}</h2>
                                <p class="text-gray-600 text-sm line-clamp-2">{{ $product->description }}</p>
                                <div class="mt-2">
                                    <span class="text-lg font-bold text-primary">@money($product->price)</span>
                                </div>
                                <div class="card-actions justify-end mt-4">
                                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary btn-sm">
                                        عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-8">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i data-lucide="box" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">لا توجد منتجات</h3>
                    <p class="text-gray-600">لم يتم العثور على منتجات تطابق معايير البحث</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>

<script>
    // Update lucide icons if needed after dynamic content loads
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    });
</script>
