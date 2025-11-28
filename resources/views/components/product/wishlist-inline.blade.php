@php
    $prod = is_object($product) ? (array) $product : (array) ($product ?? []);
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
    $discount = (int) ($prod['discount'] ?? 0);
    $finalPrice = $finalPrice ?? ($discount > 0 ? round(($prod['price'] * (100 - $discount)) / 100, 2) : ($prod['price'] ?? 0));
    $productId = $prod['id'] ?? ($prod['product_id'] ?? '');
    $title = $prod['title'] ?? '';
    $slug = $prod['slug'] ?? '#';
@endphp

<div class="flex items-center gap-3 p-2 rounded hover:bg-base-200" data-product-id="{{ $productId }}">
    <a href="{{ route('product.show', $slug) }}" class="flex items-center gap-3 flex-1">
        <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-12 h-12 object-cover rounded" />
        <div class="flex-1 min-w-0">
            <div class="text-sm font-semibold truncate">{{ $title }}</div>
            <div class="text-xs text-base-content/70 flex items-center gap-1">
                <span class="font-medium">@money($finalPrice)</span>
                @if($discount > 0)
                    <span class="line-through">@money($prod['price'] ?? 0)</span>
                @endif
            </div>
        </div>
    </a>
    <button class="btn btn-ghost btn-xs btn-circle remove-from-wishlist-navbar" data-product-id="{{ $productId }}" title="إزالة من المفضلة">

        <i data-lucide="x" class="w-4 h-4"></i>
    </button>
</div>
