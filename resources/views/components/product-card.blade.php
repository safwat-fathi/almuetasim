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
<article class="card bg-base-100 shadow-xl card-hover" itemscope itemtype="https://schema.org/Product" role="listitem">
    <a href="{{ route('product.show', $slug) }}" aria-label="عرض تفاصيل المنتج {{ $title }}">
        <figure class="relative overflow-hidden h-48">
            <img src="{{ $imageUrl }}" alt="{{ $title }}"
                loading="lazy"
                decoding="async"
                itemprop="image"
                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110" />
            @if ($hasDiscount)
                <div class="badge badge-secondary absolute top-2 left-2" aria-label="خصم {{ $discountValue }}%">{{ $discountValue }}% OFF</div>
            @elseif ($onSale ?? false)
                <div class="badge badge-secondary absolute top-2 left-2" aria-label="موجود تخفيض">تخفيض</div>
            @endif
            @if(isset($type))
                <div class="absolute top-2 right-2">
                    @if($type === 'service')
                        <div class="badge badge-info" aria-label="نوع الخدمة">خدمة</div>
                    @else
                        <div class="badge badge-success" aria-label="نوع المنتج">منتج</div>
                    @endif
                </div>
            @endif
        </figure>
        <div class="card-body">
            <h3 class="card-title text-sm" itemprop="name">{{ $title }}</h3>
            <div class="price-actions mb-4">
                <div class="price">
                    <span class="text-lg font-bold text-primary" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                        <meta itemprop="price" content="{{ $finalPrice }}">
                        <meta itemprop="priceCurrency" content="EGP">
                        <span aria-label="السعر: {{ number_format($finalPrice, 2) }} جنيه مصري">@money($finalPrice)</span>
                    </span>
                </div>
                @if ($hasDiscount)
                    <div class="original-price text-sm line-through text-base-content/50" aria-label="السعر الأصلي: {{ number_format($basePrice, 2) }} جنيه مصري">@money($basePrice)</div>
                @elseif ($onSale ?? false)
                    @php $displayOriginalPrice = $originalPrice ?? ($basePrice * 1.2); @endphp
                    <div class="original-price text-sm line-through text-base-content/50" aria-label="السعر الأصلي: {{ number_format($displayOriginalPrice, 2) }} جنيه مصري">@money($displayOriginalPrice)</div>
                @endif
            </div>
            @if(isset($category))
                <div class="text-xs text-base-content/70" aria-label="التصنيف: {{ $category }}">{{ $category }}</div>
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
</article>
