<div class="drawer-side">
    <label for="drawer-toggle" aria-label="close sidebar" class="drawer-overlay"></label>
    <aside class="min-h-full w-64 bg-base-200 text-base-content">
        <div class="p-4">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <i data-lucide="store" class="w-6 h-6 text-primary-content"></i>
                </div>
                <span class="text-xl font-bold">متجر المعتصم</span>
            </div>
        </div>
        <ul class="menu p-4 space-y-2 w-full">
            <li>
                <a href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) class="active" @endif>
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
					الرئيسية
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" @if(request()->routeIs('admin.products.*')) class="active" @endif>
                    <i data-lucide="package" class="w-5 h-5"></i>
                    المنتجات
                    <span class="badge badge-primary">{{ \App\Models\Product::count() }}</span>
                </a>
            </li>
            {{-- <li>
                <a>
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    Orders
                </a>
            </li>
            <li>
                <a>
                    <i data-lucide="users" class="w-5 h-5"></i>
                    Customers
                </a>
            </li>
            <li>
                <a>
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    Analytics
                </a>
            </li> --}}
            <li>
                <a href="{{ route('admin.categories.index') }}" @if(request()->routeIs('admin.categories.*')) class="active" @endif>
                    <i data-lucide="tag" class="w-5 h-5"></i>
                    الفئات
                </a>
            </li>
            <li>
                <a href="{{ route('admin.messages.index') }}" @if(request()->routeIs('admin.messages.*')) class="active" @endif>
                    <i data-lucide="mail" class="w-5 h-5"></i>
                    الرسائل
                    <span class="badge badge-primary"
                        id="messages-count">{{ \App\Models\Message::where('read', false)->count() }}</span>
                </a>
            </li>
            {{-- <li>
                <a>
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    Settings
                </a>
            </li> --}}
        </ul>
    </aside>
</div>