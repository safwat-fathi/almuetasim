@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'قائمة الأمنيات')

<x-layouts.app>
    <div class="min-h-screen bg-base-200 py-8">
        <div class="container mx-auto px-4">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-primary mb-4">قائمة الأمنيات</h1>
                <p class="text-lg text-base-content/70">المنتجات التي أضفتها إلى قائمة الأمنيات</p>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8" id="wishlist-grid">
                    @foreach($products as $product)
                        @php
                            $images = $product->images ?? [];
                            $processedImages = [];
                            foreach ($images as $image) {
                                if (Str::startsWith($image, ['http://', 'https://', '/'])) {
                                    $processedImages[] = $image;
                                } else {
                                    $processedImages[] = Storage::url($image);
                                }
                            }
                            $mainImage = !empty($processedImages)
                                ? $processedImages[0]
                                : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop&crop=center';
                            
                            $discount = (int) ($product->discount ?? 0);
                            $finalPrice = $discount > 0 ? round(($product->price * (100 - $discount)) / 100, 2) : $product->price;
                        @endphp
                        <x-product.wishlist-card :product="$product" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i data-lucide="heart" class="w-16 h-16 mx-auto text-base-content/40 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">قائمة الأمنيات فارغة</h3>
                    <p class="text-base-content/70 mb-6">لم تقم بإضافة أي منتجات إلى قائمة الأمنيات بعد</p>
                    <a href="{{ route('products.public.list') }}" class="btn btn-primary">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        تصفح المنتجات
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>

<script>
    (function() {
        // Remove from wishlist functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-from-wishlist')) {
                const button = e.target.closest('.remove-from-wishlist');
                const productId = button.getAttribute('data-product-id');
                const card = button.closest('[data-product-id]');
                
                // Show loading state
                button.disabled = true;
                button.classList.add('loading');
                
                fetch(`/wishlist/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the card with animation
                        card.style.transition = 'opacity 0.3s, transform 0.3s';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        
                        setTimeout(() => {
                            card.remove();
                            
                            // Update navbar count
                            const wishlistCountEl = document.getElementById('wishlist-count');
                            if (wishlistCountEl) {
                                wishlistCountEl.textContent = data.count;
                                wishlistCountEl.style.display = data.count > 0 ? 'block' : 'none';
                            }
                            
                            // Dispatch custom event to update navbar
                            document.dispatchEvent(new CustomEvent('wishlistUpdated', { detail: { count: data.count } }));
                            
                            // Check if wishlist is empty and show empty state
                            const grid = document.getElementById('wishlist-grid');
                            if (grid && grid.children.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Error removing from wishlist:', error);
                    button.disabled = false;
                    button.classList.remove('loading');
                });
            }
        });
        
      
       
    })();
</script>

