<div class="card bg-base-100 shadow-xl card-hover">
    <a href="{{ route('product.show', $slug) }}">
        <figure class="relative overflow-hidden h-48">
            <img src="{{ $image }}" alt="{{ $title }}"
                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110" />
            @if ($onSale ?? false)
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
                    <span class="text-lg font-bold text-primary">{{ number_format($price, 2) }} ج.م</span>
                </div>
                @if ($onSale ?? false)
                    @php
                        $displayOriginalPrice = $originalPrice ?? ($price * 1.2); // Default to 20% higher if not provided
                    @endphp
                    <div class="original-price text-sm line-through text-base-content/50">{{ number_format($displayOriginalPrice, 2) }} ج.م</div>
                @endif
            </div>
            @if(isset($category))
                <div class="text-xs text-base-content/70">{{ $category }}</div>
            @endif
            {{-- <div class="card-actions">
                <button class="btn btn-primary btn-sm flex-1"
                    onclick="addToCartGeneric({{ $id }}, '{{ $title }}', {{ $price }}, '{{ $image }}')">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    أضف إلى السلة
                </button>
                <button class="btn btn-ghost btn-sm btn-circle">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                </button>
            </div> --}}
        </div>
    </a>
</div>
