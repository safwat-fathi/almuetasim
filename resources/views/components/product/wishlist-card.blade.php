@php
    // Full-size wishlist card used on wishlist.index
    $prod = is_object($product) ? $product : (object) ($product ?? []);
    $images = $prod->images ?? [];
    $processedImages = [];
    foreach ($images as $image) {
        if (Str::startsWith($image, ['http://', 'https://', '/'])) {
            $processedImages[] = $image;
        } else {
            $processedImages[] = Storage::url($image);
        }
    }
    $mainImage = !empty($processedImages) ? $processedImages[0] : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop&crop=center';
    $discount = (int) ($prod->discount ?? 0);
    $finalPrice = $discount > 0 ? round(($prod->price * (100 - $discount)) / 100, 2) : $prod->price;
@endphp

<div class="card bg-base-100 shadow-xl card-hover" data-product-id="{{ $prod->id }}">
    <a href="{{ route('product.show', $prod->slug) }}">
        <figure class="relative overflow-hidden h-48">
            <img src="{{ $mainImage }}" alt="{{ $prod->title }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-110" />
            @if ($discount > 0)
                <div class="badge badge-secondary absolute top-2 left-2">{{ $discount }}% OFF</div>
            @endif
            @if($prod->type === 'service')
                <div class="badge badge-info absolute top-2 right-2">خدمة</div>
            @else
                <div class="badge badge-success absolute top-2 right-2">منتج</div>
            @endif
        </figure>
    </a>
    <div class="card-body">
        <h2 class="card-title text-lg">
            <a href="{{ route('product.show', $prod->slug) }}" class="hover:text-primary">{{ $prod->title }}</a>
        </h2>
        <p class="text-base-content/70 text-sm line-clamp-2">{{ $prod->description }}</p>
        <div class="mt-2">
            <div class="flex items-center gap-2">
                <span class="text-lg font-bold text-primary">@money($finalPrice)</span>
                @if ($discount > 0)
                    <span class="text-sm line-through text-base-content/50">@money($prod->price)</span>
                @endif
            </div>
        </div>
        @if($prod->category)
            <div class="text-xs text-base-content/70">{{ $prod->category->name }}</div>
        @endif
        <div class="card-actions justify-between mt-4">
            <a href="{{ route('product.show', $prod->slug) }}" class="btn btn-primary btn-sm">
                <i data-lucide="eye" class="w-4 h-4"></i>
                عرض التفاصيل
            </a>
            <button class="btn btn-ghost btn-sm btn-circle remove-from-wishlist" data-product-id="{{ $prod->id }}" title="إزالة من قائمة الأمنيات">
                <i data-lucide="heart" class="w-5 h-5 fill-current text-error"></i>
            </button>
        </div>
    </div>
</div>
