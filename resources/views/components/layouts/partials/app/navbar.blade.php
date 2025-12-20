@php
    use App\Http\Controllers\WishlistController;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    
    $categories = \App\Models\Category::orderBy('name')->get();
    $currentCategorySlug = request()->route('categorySlug');
    $isCategoryRoute = request()->routeIs('category.show');
    $navLinkBase = 'group flex items-center gap-2 px-4 py-2 rounded-md transition-colors duration-150';
    
    // Get wishlist count and top products
    $wishlistCount = 0;
    $wishlistTopProducts = [];
    if (session()->has('wishlist')) {
        $wishlistCount = count(session()->get('wishlist', []));
        $wishlistTopProducts = WishlistController::getTopProducts();
    }

    // Get cart items for navbar dropdown
    $cartItems = session()->get('cart', []);
@endphp

<nav class="navbar shadow-lg sticky top-0 z-50" style="background-color: #f8fafc;">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16">
                    </path>
                </svg>
            </div>
            <ul tabindex="0" class="dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-56 p-3 shadow space-y-1">
                <li>
                    {{-- <a href="{{ route('home') }}" @class([
                        $navLinkBase,
                        'bg-[#2d3b61]' => request()->routeIs('home'),
                        'text-white' => request()->routeIs('home'),
                        '!text-white' => request()->routeIs('home'),
                        'text-base-content' => !request()->routeIs('home'),
                        'hover:bg-[#2d3b61]' => true,
                        'hover:text-white' => true,
                    ])>
                        <i data-lucide="home" @class([
                            'w-4 h-4',
                            'group-hover:text-white' => true,
                            'text-white' => request()->routeIs('home'),
                        ])></i>
                        الرئيسية
                    </a> --}}
                </li>
                <li>
                    {{-- <a href="{{ route('about') }}" @class([
                        $navLinkBase,
                        'bg-[#2d3b61]' => request()->routeIs('about'),
                        'text-white' => request()->routeIs('about'),
                        '!text-white' => request()->routeIs('about'),
                        'text-base-content' => !request()->routeIs('about'),
                        'hover:bg-[#2d3b61]' => true,
                        'hover:text-white' => true,
                    ])>
                        <i data-lucide="info" @class([
                            'w-4 h-4',
                            'group-hover:text-white' => true,
                            'text-white' => request()->routeIs('about'),
                        ])></i>
                        من نحن
                    </a> --}}
                </li>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('category.show', $category) }}" @class([
                            $navLinkBase,
                            'bg-[#2d3b61]' => $currentCategorySlug === $category->slug,
                            'text-white' => $currentCategorySlug === $category->slug,
                            '!text-white' => $currentCategorySlug === $category->slug,
                            'text-base-content' => $currentCategorySlug !== $category->slug,
                            'hover:bg-[#2d3b61]' => true,
                            'hover:text-white' => true,
                        ])>
                            <i data-lucide="{{ $category->icon ?? 'box' }}"
                                @class([
                                    'w-4 h-4',
                                    'group-hover:text-white' => true,
                                    'text-white' => $currentCategorySlug === $category->slug,
                                ])></i>
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/ALMUETASIM-150x106.png') }}" class="w-16 h-14" alt="Logo">
        </a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 gap-1 items-center">
            <li>
                {{-- <a href="{{ route('home') }}" @class([
                    $navLinkBase,
                    'bg-[#2d3b61]' => request()->routeIs('home'),
                    'text-white' => request()->routeIs('home'),
                    '!text-white' => request()->routeIs('home'),
                    'text-base-content' => !request()->routeIs('home'),
                    'hover:bg-[#2d3b61]' => true,
                    'hover:text-white' => true,
                ])>
                    <i data-lucide="home" @class([
                        'w-4 h-4',
                        'group-hover:text-white' => true,
                        'text-white' => request()->routeIs('home'),
                    ])></i>
                    الرئيسية
                </a> --}}
            </li>
            @foreach ($categories as $category)
                <li>
                    <a href="{{ route('category.show', $category) }}" @class([
                        $navLinkBase,
                        'group/item',
                        'bg-[#2d3b61]' => $currentCategorySlug === $category->slug,
                        'text-white' => $currentCategorySlug === $category->slug,
                        '!text-white' => $currentCategorySlug === $category->slug,
                        'text-base-content' => $currentCategorySlug !== $category->slug,
                        'hover:bg-[#2d3b61]' => true,
                        'hover:text-white' => true,
                    ])>
                        <i data-lucide="{{ $category->icon ?? 'box' }}" @class([
                            'w-4 h-4',
                            'group-hover/item:text-white' => true,
                            'text-white' => $currentCategorySlug === $category->slug,
                            'text-base-content' => $currentCategorySlug !== $category->slug,
                        ])></i>
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
						<li>
                {{-- <a href="{{ route('about') }}" @class([
                    $navLinkBase,
                    'bg-[#2d3b61]' => request()->routeIs('about'),
                    'text-white' => request()->routeIs('about'),
                    '!text-white' => request()->routeIs('about'),
                    'text-base-content' => !request()->routeIs('about'),
                    'hover:bg-[#2d3b61]' => true,
                    'hover:text-white' => true,
                ])>
                    <i data-lucide="info" @class([
                        'w-4 h-4',
                        'group-hover:text-white' => true,
                        'text-white' => request()->routeIs('about'),
                    ])></i>
                    من نحن
                </a> --}}
            </li>
        </ul>
    </div>

    <div class="navbar-end gap-2">
        <!-- Global search (shows top 4 results in dropdown) -->
        <div class="relative" id="global-search-wrapper">
            <div class="form-control">
                <div class="input-group hidden lg:flex">
                    <input id="global-search-input" type="text" placeholder="ابحث عن منتج..."
                        class="input input-bordered w-80" autocomplete="off" />

                </div>
            </div>

            <!-- Dropdown suggestions -->
            <div id="global-search-dropdown"
                class="absolute right-0 mt-2 w-96 bg-base-100 shadow-lg rounded-box p-2 hidden z-[60]">
                <div id="global-search-results" class="space-y-2">
                    <!-- Populated dynamically with up to 4 items -->
                </div>
                <div class="mt-2 text-right">
                    <a id="global-search-see-all" href="#"
                        class="text-sm text-primary flex items-center gap-1 justify-start">
                        <i data-lucide="arrow-left" class="w-3 h-3"></i>
                        عرض كل النتائج </a>
                </div>
            </div>
        </div>

        <!-- Wishlist Dropdown -->
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" id="wishlist-button" class="btn btn-ghost btn-circle" aria-haspopup="true" aria-expanded="false" data-wishlist-dropdown-url="{{ route('wishlist.dropdown') }}">
                <div class="indicator">
                    <i data-lucide="heart"></i>
                    @if($wishlistCount > 0)
                        <span class="badge badge-sm indicator-item text-white" id="wishlist-count"
                            style="background-color: #2d3b61;">{{ $wishlistCount }}</span>
                    @else
                        <span class="badge badge-sm indicator-item text-white hidden" id="wishlist-count"
                            style="background-color: #2d3b61;">0</span>
                    @endif
                </div>
            </div>

            <script>
                (function() {
                    // When the wishlist button is clicked, fetch the latest dropdown HTML
                    var btn = document.getElementById('wishlist-button');
                    if (!btn) return;

                    btn.addEventListener('click', function() {
                        var url = btn.getAttribute('data-wishlist-dropdown-url') || '/wishlist/dropdown';
                        fetch(url, { credentials: 'same-origin' })
                            .then(function(res) { return res.json(); })
                            .then(function(json) {
                                if (!json) return;
                                if (json.dropdownHtml !== undefined) {
                                    var container = document.getElementById('wishlist-items');
                                    if (container) container.innerHTML = json.dropdownHtml;
                                }
                                if (json.count !== undefined) {
                                    var counter = document.getElementById('wishlist-count');
                                    if (counter) {
                                        if (json.count > 0) {
                                            counter.textContent = json.count;
                                            counter.classList.remove('hidden');
                                        } else {
                                            counter.textContent = '0';
                                            counter.classList.add('hidden');
                                        }
                                    }
                                }
                            }).catch(function() {
                                // ignore failures silently
                            });
                    });
                })();
            </script>

            <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-[1] mt-3 w-80 shadow-xl">
                <div class="card-body">
                    <span class="text-lg font-bold">المفضلة</span>

                    <div id="wishlist-items" class="space-y-2 max-h-64 overflow-y-auto">
                        @if(count($wishlistTopProducts) > 0)
                            @foreach($wishlistTopProducts as $product)
                                @php
                                    $images = $product['images'] ?? [];
                                    $mainImage = null;
                                    if (!empty($images)) {
                                        $image = $images[0];
                                        if (Str::startsWith($image, ['http://', 'https://', '/'])) {
                                            $mainImage = $image;
                                        } else {
                                            $mainImage = Storage::url($image);
                                        }
                                    } else {
                                        $mainImage = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=150&fit=crop&crop=center';
                                    }
                                    $discount = (int) ($product['discount'] ?? 0);
                                    $finalPrice = $discount > 0 ? round(($product['price'] * (100 - $discount)) / 100, 2) : $product['price'];
                                @endphp
                                <x-product.wishlist-inline :product="$product" :mainImage="$mainImage" :finalPrice="$finalPrice" />
                            @endforeach
                        @else
                            <p class="text-base-content/70 text-sm">قائمة الأمنيات فارغة</p>
                        @endif
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('wishlist.index') }}" class="btn btn-block text-white"
                            style="background-color: #2d3b61; border-color: #2d3b61;">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                            عرض المفضلة

                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" id="cart-button" class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <i data-lucide="shopping-cart"></i>
                    @php
                        $cartCount = count(session()->get('cart', []));
                    @endphp
                    @if($cartCount > 0)
                        <span class="badge badge-sm indicator-item text-white" id="cart-count"
                            style="background-color: #2d3b61;">{{ $cartCount }}</span>
                    @else
                        <span class="badge badge-sm indicator-item text-white hidden" id="cart-count"
                            style="background-color: #2d3b61;">0</span>
                    @endif
                </div>
            </div>
            <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-[1] mt-3 w-80 shadow-xl">
                <div class="card-body">
                    <span class="text-lg font-bold">عناصر السلة</span>
                    <div id="cart-items" class="space-y-2 max-h-64 overflow-y-auto">
                        @if(count($cartItems) > 0)
                            @foreach($cartItems as $item)
                                <div class="cart-item flex items-center justify-between p-2 rounded hover:bg-base-200" data-product-id="{{ $item['id'] }}">
                                    <a href="{{ isset($item['slug']) ? route('product.show', $item['slug']) : '#' }}" class="flex items-center gap-2 flex-1">
                                        <img src="{{ $item['image'] ? '/storage/' . $item['image'] : 'https://placehold.co/60x60' }}" alt="{{ $item['name'] }}" class="w-12 h-12 object-cover rounded" />
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-semibold truncate">{{ $item['name'] }}</div>
                                            <div class="text-xs text-base-content/70">الكمية: {{ $item['quantity'] }}</div>
                                            <div class="text-xs flex items-center gap-1">
                                                <span class="font-medium">{{ number_format($item['price'] * $item['quantity'], 2) }} ج.م</span>
                                            </div>
                                        </div>
                                    </a>
                                    <button class="btn btn-ghost btn-xs btn-circle remove-from-cart-navbar" data-product-id="{{ $item['id'] }}" title="إزالة من السلة">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <p class="text-base-content/70 text-sm">سلة التسوق فارغة</p>
                        @endif
                    </div>
                    <div class="card-actions">
                        <a href="{{ Route::has('cart.index') ? route('cart.index') : url('/cart') }}" class="btn btn-block text-white"
                            style="background-color: #2d3b61; border-color: #2d3b61;">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            عرض السلة
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- <button class="btn btn-ghost btn-circle">
            <i data-lucide="user"></i>
        </button> --}}
    </div>
</nav>

<!-- Client-side wishlist template used by JS if needed -->
<script type="text/template" id="product-wishlist-template">
    <a href="/product/__SLUG__" class="flex items-center gap-3 p-2 rounded hover:bg-base-200">
        <img src="__IMAGE__" alt="__TITLE__" class="w-12 h-12 object-cover rounded" />
        <div class="flex-1 min-w-0">
            <div class="text-sm font-semibold truncate">__TITLE__</div>
            <div class="text-xs text-base-content/70 flex items-center gap-1">
                <span class="font-medium">__PRICE__</span>
                __ORIGINAL__
            </div>
        </div>
    </a>
</script>

<!-- Client-side cart template used by JS if needed -->
<script type="text/template" id="product-cart-template">
    <div class="cart-item" data-product-id="__ID__">
        <a href="/product/__SLUG__" class="flex items-center gap-3 p-2 rounded hover:bg-base-200">
            <img src="__IMAGE__" alt="__TITLE__" class="w-12 h-12 object-cover rounded" />
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold truncate">__TITLE__</div>
                <div class="text-xs text-base-content/70">الكمية: __QUANTITY__</div>
                <div class="text-xs text-base-content/70 flex items-center gap-1">
                    <span class="font-medium">__PRICE__</span>
                </div>
            </div>
        </a>
        <button class="btn btn-ghost btn-xs btn-circle remove-from-cart-navbar" data-product-id="__ID__" title="إزالة من السلة">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
</script>

<script>
    (function() {
        // Use event delegation + DOMContentLoaded to avoid timing issues
        let timeout;

        function attachListenersTo(el) {
            if (!el) return;
            el.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => doSearch(el.value), 400);
            });
            el.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    doSearch(el.value);
                }
            });
        }

        function initBindings() {
            // Only attach the old doSearch bindings to the page-level search input.
            // The navbar input now has its own dropdown logic and should NOT call doSearch on every keystroke
            const pageInput = document.getElementById('page-search-input');
            if (!pageInput) return;
            attachListenersTo(pageInput);

            // Event delegation: also listen for future inputs (in case content replaced)
            // Only listen for page-level search input here. Navbar input uses separate dropdown logic.
            document.addEventListener('input', function(e) {
                const t = e.target;
                if (t && t.id === 'page-search-input') {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => doSearch(t.value), 400);
                }
            });
            document.addEventListener('keypress', function(e) {
                const t = e.target;
                if (e.key === 'Enter' && t && t.id === 'page-search-input') {
                    e.preventDefault();
                    doSearch(t.value);
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initBindings);
        } else {
            initBindings();
        }

        // Also bind the sort select so changing it reapplies the current search
        function bindSortSelect() {
            const sortSelect = document.getElementById('products-sort-select');
            if (!sortSelect) return;
            sortSelect.addEventListener('change', function() {
                // Only apply sort immediately if we're on a page that has the page-level search/grid
                const pageInput = document.getElementById('page-search-input');
                if (pageInput) {
                    doSearch(pageInput.value);
                }
                // If only navbar input exists, don't auto-redirect; user can press Search to apply sort with their query.
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bindSortSelect);
        } else {
            bindSortSelect();
        }

        // View toggle (grid / list) — applies to homepage buttons if present
        function initViewToggle() {
            const container = document.querySelector('[data-view-toggle-enabled]');
            // default: disabled unless explicit true
            if (!container || container.getAttribute('data-view-toggle-enabled') !== 'true') {
                // force grid view and exit
                const grid = document.getElementById('products-grid');
                if (grid) grid.classList.remove('list-view');
                return;
            }

            const gridBtn = document.getElementById('view-grid-btn');
            const listBtn = document.getElementById('view-list-btn');
            const grid = document.getElementById('products-grid');
            if (!grid || (!gridBtn && !listBtn)) return;

            function applyView(view) {
                const activeClass = 'btn-active';
                if (view === 'list') {
                    grid.classList.add('list-view');
                    if (gridBtn) {
                        gridBtn.setAttribute('aria-pressed', 'false');
                        gridBtn.classList.remove(activeClass);
                    }
                    if (listBtn) {
                        listBtn.setAttribute('aria-pressed', 'true');
                        listBtn.classList.add(activeClass);
                    }
                } else {
                    grid.classList.remove('list-view');
                    if (gridBtn) {
                        gridBtn.setAttribute('aria-pressed', 'true');
                        gridBtn.classList.add(activeClass);
                    }
                    if (listBtn) {
                        listBtn.setAttribute('aria-pressed', 'false');
                        listBtn.classList.remove(activeClass);
                    }
                }
                try {
                    localStorage.setItem('products_view', view);
                } catch (e) {}
            }

            if (gridBtn) gridBtn.addEventListener('click', function() {
                applyView('grid');
            });
            if (listBtn) listBtn.addEventListener('click', function() {
                applyView('list');
            });

            // restore
            let saved = null;
            try {
                saved = localStorage.getItem('products_view');
            } catch (e) {}
            applyView(saved === 'list' ? 'list' : 'grid');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initViewToggle);
        } else {
            initViewToggle();
        }

        async function doSearch(q) {
            // Only update homepage grid if present
            const grid = document.getElementById('products-grid');
            const sortSelect = document.getElementById('products-sort-select');
            const sort = sortSelect ? sortSelect.value : null;
            if (!grid) {
                // Not on homepage — redirect to products page with search param
                const params = new URLSearchParams();
                if (q) params.set('search', q);
                if (sort) params.set('sort', sort);
                window.location = '/products?' + params.toString();
                return;
            }

            const params = new URLSearchParams();
            if (q) params.set('search', q);
            if (sort) params.set('sort', sort);
            const url = '/api/products?' + params.toString();

            try {
                const grid = document.getElementById('products-grid');
                if (grid) grid.setAttribute('data-loading', 'true');

                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const ct = res.headers.get('content-type') || '';
                if (!res.ok || !ct.includes('application/json')) {
                    // fallback to full navigation
                    if (grid) grid.removeAttribute('data-loading');
                    window.location = url;
                    return;
                }

                const data = await res.json();
                grid.innerHTML = '';
                if (!data.products || data.products.length === 0) {
                    grid.innerHTML =
                        `<div class="col-span-full text-center py-10 text-base-content/60">لم يتم العثور على منتجات مطابقة لبحثك.</div>`;
                } else {
                    for (const p of data.products) {
                        grid.insertAdjacentHTML('beforeend', renderProductCard(p));
                    }
                }
                if (grid) grid.removeAttribute('data-loading');
            } catch (err) {
                console.error('Search error', err);
                const grid = document.getElementById('products-grid');
                if (grid) grid.removeAttribute('data-loading');
                window.location = url;
            }
        }

        function renderProductCard(p) {
            const image = (p.images && p.images[0]) ? ('/storage/' + p.images[0].replace(/^\/+/, '')) :
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop&crop=center';
            const title = escapeHtml(p.title || '');
            const egpFormatter = new Intl.NumberFormat('ar-EG', { style: 'currency', currency: 'EGP' });
            const price = egpFormatter.format(Number(p.price || 0));
            const category = p.category && p.category.name ? escapeHtml(p.category.name) : '';
            const slug = p.slug || '#';

            return `
                <div class="card bg-base-100 shadow-xl card-hover">
                    <a href="/product/${encodeURIComponent(slug)}">
                        <figure class="relative overflow-hidden h-48">
                            <img src="${image}" alt="${title}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-110" />
                        </figure>
                        <div class="card-body">
                            <h3 class="card-title text-sm">${title}</h3>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-lg font-bold text-primary">${price}</span>
                            </div>
                            ${category ? `<div class="text-xs text-base-content/70">${category}</div>` : ''}
                        </div>
                    </a>
                </div>
            `;
        }

        function escapeHtml(unsafe) {
            return (unsafe || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    })();
</script>

<script>
    (function() {
        // Navbar search dropdown logic
        let navTimeout;
        const input = document.getElementById('global-search-input');
        const dropdown = document.getElementById('global-search-dropdown');
        const resultsContainer = document.getElementById('global-search-results');
        const seeAll = document.getElementById('global-search-see-all');
        const searchBtn = document.getElementById('global-search-btn');

        function closeDropdown() {
            if (dropdown) dropdown.classList.add('hidden');
        }

        function openDropdown() {
            if (dropdown) dropdown.classList.remove('hidden');
        }

        function renderItem(p) {
            const image = (p.images && p.images[0]) ? ('/storage/' + p.images[0].replace(/^\/+/, '')) :
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=150&fit=crop&crop=center';
            const title = escapeHtml(p.title || '');
            const egpFormatter = new Intl.NumberFormat('ar-EG', { style: 'currency', currency: 'EGP' });
            const price = egpFormatter.format(Number(p.price || 0));
            const slug = p.slug || '#';
            return `
                <a href="/product/${encodeURIComponent(slug)}" class="flex items-center gap-3 p-2 rounded hover:bg-base-200">
                    <img src="${image}" alt="${title}" class="w-12 h-10 object-cover rounded" />
                    <div class="flex-1">
                        <div class="text-sm font-semibold">${title}</div>
                        <div class="text-xs text-base-content/70 flex items-center gap-1">
                            <i data-lucide="dollar-sign" class="w-3 h-3"></i>
                            ${price}
                        </div>
                    </div>
                </a>
            `;
        }

        function escapeHtml(unsafe) {
            return (unsafe || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        async function fetchTop(q) {
            if (!q || q.trim().length === 0) {
                resultsContainer.innerHTML = '';
                closeDropdown();
                return;
            }
            const url = '/api/products?search=' + encodeURIComponent(q) + '&limit=4';
            try {
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!res.ok) return;
                const data = await res.json();
                const prods = data.products || [];
                resultsContainer.innerHTML = '';
                if (prods.length === 0) {
                    resultsContainer.innerHTML =
                        `<div class="py-2 text-center text-sm text-base-content/60">لا توجد نتائج</div>`;
                    openDropdown();
                    return;
                }
                for (const p of prods) {
                    resultsContainer.insertAdjacentHTML('beforeend', renderItem(p));
                }
                // update see all link
                if (seeAll) seeAll.setAttribute('href', '/products?search=' + encodeURIComponent(q));
                openDropdown();
            } catch (e) {
                console.error('Navbar search error', e);
            }
        }

        if (input) {
            input.addEventListener('input', function(e) {
                clearTimeout(navTimeout);
                navTimeout = setTimeout(() => fetchTop(input.value), 300);
                // Update see all link immediately
                if (seeAll) seeAll.setAttribute('href', '/products?search=' + encodeURIComponent(input
                    .value));
            });

            input.addEventListener('focus', function() {
                if (input.value) fetchTop(input.value);
            });
            input.addEventListener('blur', function() {
                setTimeout(closeDropdown, 200);
            });
        }

        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                const q = input ? input.value : '';
                const params = new URLSearchParams();
                if (q) params.set('search', q);
                window.location = '/products?' + params.toString();
            });
        }

    })();
</script>

<script>
    (function() {
        // Function to update wishlist count in navbar
        function updateWishlistCount(count) {
            const wishlistCountEl = document.getElementById('wishlist-count');
            if (wishlistCountEl) {
                wishlistCountEl.textContent = count;
                if (count > 0) {
                    wishlistCountEl.style.display = 'block';
                } else {
                    wishlistCountEl.style.display = 'none';
                }
            }
        }

        // Function to refresh wishlist dropdown (reload page to get updated products)
        function refreshWishlistDropdown() {
            // Reload the page to get updated wishlist items
            // This is simpler than dynamically updating the dropdown
            // The dropdown will be updated on next open
        }

        // Listen for custom events from other pages
        document.addEventListener('wishlistUpdated', function(e) {
            if (e.detail) {
                if (typeof e.detail.count !== 'undefined') {
                    updateWishlistCount(e.detail.count);
                }
                if (e.detail.dropdownHtml) {
                    const wishlistItems = document.getElementById('wishlist-items');
                    if (wishlistItems) {
                        wishlistItems.innerHTML = e.detail.dropdownHtml;
                    }
                }
            }
        });


        // Also listen for storage events (in case of multiple tabs)
        window.addEventListener('storage', function(e) {
            if (e.key === 'wishlist') {
                // Reload count from session
                fetch('/wishlist/count')
                    .then(response => response.json())
                    .then(data => {
                        updateWishlistCount(data.count);
                    });
            }
        });

        // Remove from wishlist in navbar dropdown
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-from-wishlist-navbar')) {
                const button = e.target.closest('.remove-from-wishlist-navbar');
                const productId = button.getAttribute('data-product-id');
                const item = button.closest('[data-product-id]');
                
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
                        // Remove the item with animation
                        item.style.transition = 'opacity 0.3s, transform 0.3s';
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(-10px)';
                        
                        setTimeout(() => {
                            item.remove();
                            
                            // Update count
                            updateWishlistCount(data.count);
                            
                            // Dispatch custom event
                            document.dispatchEvent(new CustomEvent('wishlistUpdated', { 
                                detail: { 
                                    count: data.count,
                                    dropdownHtml: data.dropdownHtml
                                } 
                            }));

                            
                            // Check if wishlist is empty
                            const wishlistItems = document.getElementById('wishlist-items');
                            if (wishlistItems && wishlistItems.children.length === 0) {
                                wishlistItems.innerHTML = '<p class="text-base-content/70 text-sm">قائمة الأمنيات فارغة</p>';
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

<script>
    (function() {
        // Function to update cart count in navbar
        function updateCartCount(count) {
            const cartCountEl = document.getElementById('cart-count');
            if (cartCountEl) {
                cartCountEl.textContent = count;
                if (count > 0) {
                    cartCountEl.style.display = 'block';
                } else {
                    cartCountEl.style.display = 'none';
                }
            }
        }

        // Function to refresh cart dropdown
        function refreshCartDropdown() {
            fetch('/cart/items')
                .then(response => response.json())
                .then(data => {
                    const cartItems = document.getElementById('cart-items');
                    if (cartItems) {
                        if (data.items && data.items.length > 0) {
                            cartItems.innerHTML = '';
                            data.items.forEach(function(item) {
                                let priceFormatter = new Intl.NumberFormat('ar-EG', { style: 'currency', currency: 'EGP' });
                                let formattedPrice = priceFormatter.format(item.price * item.quantity);

                                let cartItemHtml = `
                                    <div class="cart-item flex items-center justify-between p-2 rounded hover:bg-base-200" data-product-id="${item.id}">
                                        <a href="/product/${item.slug || ''}" class="flex items-center gap-2 flex-1">
                                            <img src="${item.image || 'https://placehold.co/60x60'}" alt="${item.name}" class="w-12 h-12 object-cover rounded" />
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-semibold truncate">${item.name}</div>
                                                <div class="text-xs text-base-content/70">الكمية: ${item.quantity}</div>
                                                <div class="text-xs flex items-center gap-1">
                                                    <span class="font-medium">${formattedPrice}</span>
                                                </div>
                                            </div>
                                        </a>
                                        <button class="btn btn-ghost btn-xs btn-circle remove-from-cart-navbar" data-product-id="${item.id}" title="إزالة من السلة">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                `;
                                cartItems.insertAdjacentHTML('beforeend', cartItemHtml);
                            });
                            // Update lucide icons
                            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                                window.lucide.createIcons();
                            }
                        } else {
                            cartItems.innerHTML = '<p class="text-base-content/70 text-sm">سلة التسوق فارغة</p>';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error refreshing cart dropdown:', error);
                });
        }

        // Function to show toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = 'toast toast-bottom toast-start z-50';

            toast.innerHTML = `
                <div class='alert alert-${type}'>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Function to add product to cart
        window.addToCart = function(productId, title, price, image) {
            fetch(`/cart/add/${productId}`, {
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
                    updateCartCount(data.count);
                    refreshCartDropdown(); // Update dropdown content
                    showToast('تمت إضافة المنتج إلى السلة!', 'success');

                    // Dispatch custom event to update cart dropdown
                    document.dispatchEvent(new CustomEvent('cartUpdated', {
                        detail: {
                            count: data.count,
                            message: data.message
                        }
                    }));
                } else {
                    showToast('حدث خطأ أثناء إضافة المنتج إلى السلة', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showToast('حدث خطأ أثناء إضافة المنتج إلى السلة', 'error');
            });
        };

        // Generic function to add product to cart (used in product cards)
        window.addToCartGeneric = function(productId, title, price, image) {
            fetch(`/cart/add/${productId}`, {
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
                    updateCartCount(data.count);
                    refreshCartDropdown(); // Update dropdown content
                    showToast('تمت إضافة المنتج إلى السلة!', 'success');

                    // Dispatch custom event to update cart dropdown
                    document.dispatchEvent(new CustomEvent('cartUpdated', {
                        detail: {
                            count: data.count,
                            message: data.message
                        }
                    }));
                } else {
                    showToast('حدث خطأ أثناء إضافة المنتج إلى السلة', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showToast('حدث خطأ أثناء إضافة المنتج إلى السلة', 'error');
            });
        };

        // Listen for custom events from other pages
        document.addEventListener('cartUpdated', function(e) {
            if (e.detail && typeof e.detail.count !== 'undefined') {
                updateCartCount(e.detail.count);
            }
        });

        // Also listen for storage events (in case of multiple tabs)
        window.addEventListener('storage', function(e) {
            if (e.key === 'cart') {
                // Reload count from session
                fetch('/cart/count')
                    .then(response => response.json())
                    .then(data => {
                        updateCartCount(data.count);
                        refreshCartDropdown(); // Update dropdown content
                    });
            }
        });

        // Remove from cart in navbar dropdown
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-from-cart-navbar')) {
                const button = e.target.closest('.remove-from-cart-navbar');
                const productId = button.getAttribute('data-product-id');
                const item = button.closest('[data-product-id]');

                // Show loading state
                button.disabled = true;
                button.classList.add('loading');

                fetch(`/cart/remove/${productId}`, {
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
                        // Remove the item with animation
                        item.style.transition = 'opacity 0.3s, transform 0.3s';
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(-10px)';

                        setTimeout(() => {
                            item.remove();

                            // Update count
                            updateCartCount(data.count);

                            // Refresh dropdown
                            refreshCartDropdown();

                            // Dispatch custom event
                            document.dispatchEvent(new CustomEvent('cartUpdated', {
                                detail: {
                                    count: data.count
                                }
                            }));

                            // Check if cart is empty
                            const cartItems = document.getElementById('cart-items');
                            if (cartItems && cartItems.children.length === 0) {
                                cartItems.innerHTML = '<p class="text-base-content/70 text-sm">سلة التسوق فارغة</p>';
                            }
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Error removing from cart:', error);
                    button.disabled = false;
                    button.classList.remove('loading');
                });
            }
        });

        // Ensure dropdown is populated when user opens the cart
        var cartBtn = document.getElementById('cart-button');
        if (cartBtn) {
            cartBtn.addEventListener('click', function() {
                try {
                    refreshCartDropdown();
                } catch (err) {
                    console.error('Failed to refresh cart dropdown on open:', err);
                }
            });
        }
    })();
</script>
