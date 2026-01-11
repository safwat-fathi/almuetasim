@php
$discountValue = isset($discount) ? (int) $discount : 0;
$hasDiscount = $discountValue > 0;
$basePrice = (float) ($price ?? 0);
$finalPrice = $hasDiscount ? round($basePrice * (100 - $discountValue) / 100, 2) : $basePrice;

// Resolve image URL to ensure it points to /storage/uploads when needed
$imageUrl = $image ?? null;
if ($imageUrl) {
	if (Str::startsWith($imageUrl, '/uploads')) {
		$imageUrl = '/storage' . $imageUrl;
	} elseif (!Str::startsWith($imageUrl, ['http://', 'https://', '/storage'])) {
		$imageUrl = Storage::url($imageUrl) ?? asset('storage/' . ltrim($imageUrl, '/'));
	}
} else {
	$imageUrl = 'https://placehold.co/400x400';
}
@endphp
<div class="card bg-base-100 shadow-xl card-hover">
    <a href="{{ route('product.show', $slug) }}">
        <figure class="relative overflow-hidden h-48">
            <img src="{{ $imageUrl }}" alt="{{ $title }}"
                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110" />
            @if ($hasDiscount)
                <div class="badge badge-secondary absolute top-2 left-2">{{ $discountValue }}% OFF</div>
            @elseif ($onSale ?? false)
                <div class="badge badge-secondary absolute top-2 left-2">تخفيض</div>
            @endif
            @if(isset($type))
                <div class="absolute top-2 right-2">
                    @if($type === 'service')
                        <div class="badge badge-info">خدمة</div>
                    @else
                        <div class="badge badge-success">منتج</div>
                    @endif
                </div>
            @endif
        </figure>
        <div class="card-body">
            <h3 class="card-title text-sm">{{ $title }}</h3>
            <div class="price-actions mb-4">
                <div class="price">
                    <span class="text-lg font-bold text-primary">@money($finalPrice)</span>
                </div>
                @if ($hasDiscount)
                    <div class="original-price text-sm line-through text-base-content/50">@money($basePrice)</div>
                @elseif ($onSale ?? false)
                    @php $displayOriginalPrice = $originalPrice ?? ($basePrice * 1.2); @endphp
                    <div class="original-price text-sm line-through text-base-content/50">@money($displayOriginalPrice)</div>
                @endif
            </div>
            @if(isset($category))
                <div class="text-xs text-base-content/70">{{ $category }}</div>
            @endif
				</div>
				</a>
				<div class="card-body pt-0">
					<div class="card-actions">
						<button class="btn btn-primary btn-sm flex-1"
							onclick="addToCartGeneric({{ $id }}, '{{ $title }}', {{ $price }}, '{{ $image }}')">
							<i data-lucide="shopping-cart" class="w-4 h-4"></i>
							أضف إلى السلة
						</button>
						<button class="btn btn-ghost btn-sm btn-circle">
							<i data-lucide="heart" class="w-4 h-4"></i>
						</button>
					</div>
				</div>
</div>
