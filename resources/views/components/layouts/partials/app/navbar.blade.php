@php
    $categories = \App\Models\Category::limit(4)->get();
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
            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                @foreach ($categories as $category)
                    <li><a href="{{ route('category.show', $category) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <a href="{{ url('/') }}">
					<img src="{{ asset('images/ALMUETASIM-150x106.png') }}" class="w-16 h-14" alt="Logo">
				</a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 gap-2">
            @foreach ($categories as $category)
                <li><a href="{{ route('category.show', $category) }}"
                        @class([
                            'hover:text-white' => request()->route()->parameter('categorySlug') != $category->slug,
                            'text-white' =>
                                request()->route()->parameter('categorySlug') == $category->slug,
                        ])
                        style="background-color: {{ request()->route()->parameter('categorySlug') == $category->slug ? '#2d3b61' : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='#2d3b61'; this.style.color='white';"
                        onmouseout="this.style.backgroundColor='{{ request()->route()->parameter('categorySlug') == $category->slug ? '#2d3b61' : 'transparent' }}'; this.style.color='{{ request()->route()->parameter('categorySlug') == $category->slug ? 'white' : 'inherit' }}';"
                        >{{ $category->name }}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="navbar-end gap-2">
        <!-- Global search (shows top 4 results in dropdown) -->
        <div class="relative" id="global-search-wrapper">
            <div class="form-control">
                <div class="input-group hidden lg:flex">
                    <input id="global-search-input" type="text" placeholder="ابحث عن منتج..." class="input input-bordered w-80" autocomplete="off" />
                    <button id="global-search-btn" class="btn btn-primary text-white" type="button">بحث</button>
                </div>
            </div>

            <!-- Dropdown suggestions -->
            <div id="global-search-dropdown" class="absolute right-0 mt-2 w-96 bg-base-100 shadow-lg rounded-box p-2 hidden z-[60]">
                <div id="global-search-results" class="space-y-2">
                    <!-- Populated dynamically with up to 4 items -->
                </div>
                <div class="mt-2 text-right">
                    <a id="global-search-see-all" href="#" class="text-sm text-primary">عرض كل النتائج</a>
                </div>
            </div>
        </div>

        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <i data-lucide="shopping-cart"></i>
                    <span class="badge badge-sm indicator-item text-white" id="cart-count" style="background-color: #2d3b61;">0</span>
                </div>
            </div>
            <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-[1] mt-3 w-80 shadow-xl">
                <div class="card-body">
                    <span class="text-lg font-bold">عناصر السلة</span>
                    <div id="cart-items" class="space-y-2">
                        <p class="text-base-content/70">سلة التسوق فارغة</p>
                    </div>
                    <div class="card-actions">
                        <button class="btn btn-block text-white" style="background-color: #2d3b61; border-color: #2d3b61;">عرض السلة</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- <button class="btn btn-ghost btn-circle">
            <i data-lucide="user"></i>
        </button> --}}
    </div>
</nav>

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
                try { localStorage.setItem('products_view', view); } catch (e) {}
            }

            if (gridBtn) gridBtn.addEventListener('click', function() { applyView('grid'); });
            if (listBtn) listBtn.addEventListener('click', function() { applyView('list'); });

            // restore
            let saved = null;
            try { saved = localStorage.getItem('products_view'); } catch (e) {}
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
                // Not on homepage — redirect to /products with search param
                const params = new URLSearchParams(); if (q) params.set('search', q); if (sort) params.set('sort', sort);
                window.location = '/products?' + params.toString();
                return;
            }

            const params = new URLSearchParams(); if (q) params.set('search', q); if (sort) params.set('sort', sort);
            const url = '/products?' + params.toString();

            try {
                const grid = document.getElementById('products-grid');
                if (grid) grid.setAttribute('data-loading', 'true');

                const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
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
                    grid.innerHTML = `<div class="col-span-full text-center py-10 text-base-content/60">لم يتم العثور على منتجات مطابقة لبحثك.</div>`;
                } else {
                    for (const p of data.products) {
                        grid.insertAdjacentHTML('beforeend', renderProductCard(p));
                    }
                    // Re-initialize lucide icons for any newly injected content
                    try { if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons(); } catch(e) { console.warn('lucide reinit failed', e); }
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
            const image = (p.images && p.images[0]) ? ('/storage/' + p.images[0]) : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop&crop=center';
            const title = escapeHtml(p.title || '');
            const price = Number(p.price || 0).toFixed(2);
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
                                <span class="text-lg font-bold text-primary">${price} EGP</span>
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
    (function(){
        // Navbar search dropdown logic
        let navTimeout;
        const input = document.getElementById('global-search-input');
        const dropdown = document.getElementById('global-search-dropdown');
        const resultsContainer = document.getElementById('global-search-results');
        const seeAll = document.getElementById('global-search-see-all');
        const searchBtn = document.getElementById('global-search-btn');

        function closeDropdown(){ if(dropdown) dropdown.classList.add('hidden'); }
        function openDropdown(){ if(dropdown) dropdown.classList.remove('hidden'); }

        function renderItem(p){
            const image = (p.images && p.images[0]) ? ('/storage/' + p.images[0]) : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=150&fit=crop&crop=center';
            const title = escapeHtml(p.title || '');
            const price = Number(p.price || 0).toFixed(2);
            const slug = p.slug || '#';
            return `
                <a href="/product/${encodeURIComponent(slug)}" class="flex items-center gap-3 p-2 rounded hover:bg-base-200">
                    <img src="${image}" alt="${title}" class="w-12 h-10 object-cover rounded" />
                    <div class="flex-1">
                        <div class="text-sm font-semibold">${title}</div>
                        <div class="text-xs text-base-content/70">${price} EGP</div>
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

        async function fetchTop(q){
            if(!q || q.trim().length === 0) { resultsContainer.innerHTML = ''; closeDropdown(); return; }
            const url = '/products?search=' + encodeURIComponent(q) + '&limit=4';
            try{
                const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                if(!res.ok) return;
                const data = await res.json();
                const prods = data.products || [];
                resultsContainer.innerHTML = '';
                if(prods.length === 0){
                    resultsContainer.innerHTML = `<div class="py-2 text-center text-sm text-base-content/60">لا توجد نتائج</div>`;
                    openDropdown();
                    return;
                }
                for(const p of prods){ resultsContainer.insertAdjacentHTML('beforeend', renderItem(p)); }
                // update see all link
                if(seeAll) seeAll.setAttribute('href', '/products?search=' + encodeURIComponent(q));
                openDropdown();
            }catch(e){ console.error('Navbar search error', e); }
        }

        if(input){
            input.addEventListener('input', function(e){
                clearTimeout(navTimeout);
                navTimeout = setTimeout(()=> fetchTop(input.value), 300);
            });

            input.addEventListener('focus', function(){ if(input.value) fetchTop(input.value); });
            input.addEventListener('blur', function(){ setTimeout(closeDropdown, 200); });
        }

        if(searchBtn){
            searchBtn.addEventListener('click', function(){
                const q = input ? input.value : '';
                const params = new URLSearchParams(); if(q) params.set('search', q);
                window.location = '/products?' + params.toString();
            });
        }

    })();
</script>
