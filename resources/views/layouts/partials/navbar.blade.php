@php
    $categories = \App\Models\Category::limit(4)->get();
@endphp

<nav class="navbar bg-base-100 shadow-lg sticky top-0 z-50">
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
                    @foreach($categories as $category)
                        <li><a href="{{ url('/category/' . $category->slug) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <a href="{{ url('/') }}" class="btn btn-ghost text-xl font-bold">مودرن شوب</a>
        </div>

        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                @foreach($categories as $category)
                    <li><a href="{{ url('/category/' . $category->slug) }}" class="hover:text-primary">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="navbar-end gap-2">
            <div class="form-control hidden md:block">
                <input type="text" placeholder="البحث عن المنتجات..."
                    class="input input-bordered input-sm w-32 md:w-auto" />
            </div>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                        <i data-lucide="shopping-cart"></i>
                        <span class="badge badge-sm indicator-item badge-primary" id="cart-count">0</span>
                    </div>
                </div>
                <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-[1] mt-3 w-80 shadow-xl">
                    <div class="card-body">
                        <span class="text-lg font-bold">عناصر السلة</span>
                        <div id="cart-items" class="space-y-2">
                            <p class="text-base-content/70">سلة التسوق فارغة</p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary btn-block">عرض السلة</button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-ghost btn-circle">
                <i data-lucide="user"></i>
            </button>
        </div>
    </nav>