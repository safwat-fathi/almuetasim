<div class="card bg-base-100 shadow-xl card-hover">
    <figure class="relative overflow-hidden h-48">
        <img src="{{ $image }}" alt="{{ $title }}"
            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110" />
        @if($onSale)
            <div class="badge badge-secondary absolute top-2 left-2">تخفيض</div>
        @endif
    </figure>
    <div class="card-body">
        <h3 class="card-title text-sm">{{ $title }}</h3>
        <div class="flex items-center gap-2 mb-4">
            <span class="text-lg font-bold text-primary">${{ number_format($price, 2) }}</span>
            @if($onSale)
                <span class="text-sm line-through text-base-content/50">${{ number_format($originalPrice, 2) }}</span>
            @endif
        </div>
        <div class="card-actions">
            <button class="btn btn-primary btn-sm flex-1" onclick="addToCartGeneric({{ $id }}, '{{ $title }}', {{ $price }}, '{{ $image }}')">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                أضف إلى السلة
            </button>
            <button class="btn btn-ghost btn-sm btn-circle">
                <i data-lucide="heart" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>