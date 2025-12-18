<div class="dropdown dropdown-end">
    <label tabindex="0" class="btn btn-ghost btn-circle">
        <div class="indicator">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8" />
            </svg>
            <span class="badge badge-sm indicator-item cart-count @php echo session()->get('cart') ? (collect(session()->get('cart'))->sum('quantity') > 0 ? '' : 'hidden') : 'hidden' @endphp">
                {{ session()->get('cart') ? collect(session()->get('cart'))->sum('quantity') : 0 }}
            </span>
        </div>
    </label>
    <div tabindex="0" class="mt-3 card card-compact dropdown-content w-52 bg-base-100 shadow z-[1]">
        <div class="card-body">
            <span class="font-bold text-lg">
                @php
                    $cart = session()->get('cart', []);
                    $count = collect($cart)->sum('quantity');
                @endphp
                {{ $count }} عناصر
            </span>
            <span class="text-info">
                @php
                    $total = 0;
                    foreach($cart as $item) {
                        $total += $item['price'] * $item['quantity'];
                    }
                @endphp
                المجموع: {{ number_format($total, 2) }} ج.م
            </span>
            <div class="card-actions">
                <a href="{{ route('cart.index') }}" class="btn btn-primary btn-block">عرض السلة</a>
            </div>
        </div>
    </div>
</div>