@php
    // Expecting $product as array or object, and optional $quantity, $mainImage, $finalPrice, $itemTotal
    // Optional: provide $removeClass to customize the remove button selector (e.g. 'remove-from-wishlist-navbar')
    $prod = is_object($product) ? (array) $product : (array) ($product ?? []);
    $quantity = $quantity ?? ($prod['quantity'] ?? 1);
    $mainImage = $mainImage ?? ($prod['images'][0] ?? null);
    if ($mainImage) {
        if (Str::startsWith($mainImage, ['http://', 'https://', '/'])) {
            $imageUrl = $mainImage;
        } else {
            $imageUrl = Storage::url($mainImage);
        }
    } else {
        $imageUrl = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=150&fit=crop&crop=center';
    }
    $finalPrice = $finalPrice ?? (float) ($prod['price'] ?? 0);
    $itemTotal = $itemTotal ?? ($finalPrice * $quantity);
    $title = $prod['title'] ?? '';
    $slug = $prod['slug'] ?? '#';
    $productId = $prod['id'] ?? ($prod['product_id'] ?? '');
    $removeClass = $removeClass ?? 'remove-from-cart-navbar';
@endphp
<div class="flex items-center gap-3 p-2 rounded hover:bg-base-200" data-product-id="{{ $productId }}">
    <a href="{{ route('product.show', $slug) }}" class="flex items-center gap-3 flex-1">
        <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-12 h-12 object-cover rounded" />
        <div class="flex-1 min-w-0">
            <div class="text-sm font-semibold truncate">{{ $title }}</div>
            <div class="text-xs text-base-content/70 flex items-center gap-1">
                @if(isset($showQuantity) && $showQuantity)
                    <span class="font-medium">@money($finalPrice) × {{ $quantity }}</span>
                    <span class="text-primary font-bold">= @money($itemTotal)</span>
                @else
                    <span class="font-medium">@money($finalPrice)</span>
                    @if(isset($showOriginalPrice) && $showOriginalPrice && isset($prod['price']) && $prod['price'] > $finalPrice)
                        <span class="line-through">@money($prod['price'])</span>
                    @endif
                @endif
            </div>
        </div>
    </a>
    <button 
        class="btn btn-ghost btn-xs btn-circle {{ $removeClass }}" 
        data-product-id="{{ $productId }}"
        title="إزالة">
        <i data-lucide="x" class="w-4 h-4"></i>
    </button>
</div>
