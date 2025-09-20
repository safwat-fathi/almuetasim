@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="drawer lg:drawer-open">
    <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
    <!-- Main Content -->
    <div class="drawer-content flex flex-col">
        <!-- Page Content -->
        <div class="flex-1 p-6">
            <!-- Stats Cards -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6"
            >
                <div
                    class="stat bg-gradient-to-r from-primary to-primary-focus rounded-lg shadow-lg relative"
                >
                    <div class="stat-figure text-primary-content absolute left-5">
                        <i data-lucide="package" class="w-8 h-8"></i>
                    </div>
                    <div class="stat-title ">
                        إجمالي المنتجات
                    </div>
                    <div class="stat-value ">{{ $stats['totalProducts'] ?? 0 }}</div>
                    <div class="stat-desc ">
                        @if($stats['totalProducts'] > 0)
                            ↗︎ {{ number_format((($stats['totalProducts'] - 100) / 100) * 100, 0) }}% عن الشهر الماضي
                        @else
                            No change
                        @endif
                    </div>
                </div>
{{-- 
                <div
                    class="stat bg-gradient-to-r from-secondary to-secondary-focus text-secondary-content rounded-lg shadow-lg"
                >
                    <div class="stat-figure text-secondary-content">
                        <i data-lucide="trending-up" class="w-8 h-8"></i>
                    </div>
                    <div class="stat-title text-secondary-content/80">
                        Active Products
                    </div>
                    <div class="stat-value text-secondary-content">{{ $stats['activeProducts'] ?? 0 }}</div>
                    <div class="stat-desc text-secondary-content/60">
                        {{ $stats['totalProducts'] > 0 ? number_format(($stats['activeProducts'] / $stats['totalProducts']) * 100, 0) : 0 }}% of total
                    </div>
                </div> --}}

                <div
                    class="stat bg-gradient-to-r from-accent to-accent-focus text-accent-content rounded-lg shadow-lg relative"
                >
                    <div class="stat-figure text-accent-content absolute left-5">
                        <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                    </div>
                    <div class="stat-title text-accent-content/80">تعداد المنتجات</div>
                    <div class="stat-value text-accent-content">{{ $stats['lowStockProducts'] ?? 0 }}</div>
                    <div class="stat-desc text-accent-content/60">
                        التي ستنتهي من المخزون
                    </div>
                </div>

                {{-- <div
                    class="stat bg-gradient-to-r from-info to-info-focus text-info-content rounded-lg shadow-lg"
                >
                    <div class="stat-figure text-info-content">
                        <i data-lucide="eye-off" class="w-8 h-8"></i>
                    </div>
                    <div class="stat-title text-info-content/80">Inactive</div>
                    <div class="stat-value text-info-content">{{ $stats['inactiveProducts'] ?? 0 }}</div>
                    <div class="stat-desc text-info-content/60">Hidden products</div>
                </div> --}}
                
                <div
                    class="stat bg-gradient-to-r from-success to-success-focus text-success-content rounded-lg shadow-lg relative"
                >
                    <div class="stat-figure text-success-content absolute left-5">
                        <i data-lucide="mail" class="w-8 h-8"></i>
                    </div>
                    <div class="stat-title text-success-content/80">رسائل جديدة</div>
                    <div class="stat-value text-success-content">{{ $stats['newMessages'] ?? 0 }}</div>
                    <div class="stat-desc text-success-content/60">
                        {{ $stats['totalMessages'] ?? 0 }} الرسائل الكلية
                    </div>
                </div>
            </div>

            <!-- Products Management -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"
                    >
                        <h2 class="card-title text-2xl">إدارة المخزون</h2>
                        <button class="btn btn-primary" onclick="openAddProductModal()">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
														إضافة منتج
                        </button>
                    </div>

                    <!-- Search and Filter -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <div class="form-control flex-1">
                            <div class="input-group">
                                <input
                                    type="text"
                                    placeholder="بحث عن منتج..."
                                    class="input input-bordered flex-1"
                                    id="search-input"
                                    value="{{ request('search') }}"
                                    onkeypress="handleSearchKeyPress(event)"
                                />
                                <button class="btn btn-square" onclick="applyFilters()">
                                    <i data-lucide="search" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <select
                            class="select select-bordered w-full sm:w-auto"
                            id="category-filter"
                        >
                            <option value="">كل الفئات</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select
                            class="select select-bordered w-full sm:w-auto"
                            id="status-filter"
                        >
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>متاح</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير متاح</option>
                            <option value="low-stock" {{ request('status') == 'low-stock' ? 'selected' : '' }}>مخزون قليل</option>
                        </select>
                    </div>

                    <!-- Products Table -->
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    {{-- <th>
                                        <label>
                                            <input
                                                type="checkbox"
                                                class="checkbox"
                                                id="select-all"
                                            />
                                        </label>
                                    </th> --}}
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="products-table-body">
                                @foreach($products as $product)
                                <tr>
                                    {{-- <td>
                                        <label>
                                            <input type="checkbox" class="checkbox product-checkbox" data-id="{{ $product->id }}">
                                        </label>
                                    </td> --}}
                                    <td>
                                        <div class="flex items-center space-x-3">
                                            <div class="avatar">
                                                <div class="mask mask-squircle w-12 h-12">
                                                    @if(!empty($product->images) && is_array($product->images))
                                                    <img src="{{ $product->images[0] }}" alt="{{ $product->title }}" />
                                                    @else
                                                    <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=100&h=100&fit=crop" alt="{{ $product->title }}" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $product->title }}</div>
                                                <div class="text-sm opacity-50">{{ $product->sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->category)
                                        <span class="badge badge-ghost">{{ $product->category->name }}</span>
                                        @else
                                        <span class="badge badge-ghost">غير مصنف</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        @if($product->stock > 10)
                                        <span class="badge badge-success">متاح</span>
                                        @elseif($product->stock > 0)
                                        <span class="badge badge-warning">مخزون قليل</span>
                                        @else
                                        <span class="badge badge-error">غير متاح</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown dropdown-end">
                                            <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                                                <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                            </div>
                                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                                <li><a href="{{ route('product.show', $product->slug) }}"><i data-lucide="eye" class="w-4 h-4"></i> عرض</a></li>
                                                <li><a onclick="editProduct({{ $product->id }})"><i data-lucide="edit" class="w-4 h-4"></i> تعديل</a></li>
                                                <li><a onclick="deleteProduct({{ $product->id }})" class="text-error"><i data-lucide="trash-2" class="w-4 h-4"></i> حذف</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-6">
                        <div class="join">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<dialog id="add_product_modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button
                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
            >
                ✕
            </button>
        </form>
        <h3 class="font-bold text-lg mb-4">Add New Product</h3>

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Product Name</span>
                    </label>
                    <input
                        type="text"
                        class="input input-bordered"
                        id="product-name"
                        placeholder="Enter product name"
                    />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">SKU</span>
                    </label>
                    <input
                        type="text"
                        class="input input-bordered"
                        id="product-sku"
                        placeholder="Product SKU"
                    />
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Description</span>
                </label>
                <textarea
                    class="textarea textarea-bordered h-24"
                    id="product-description"
                    placeholder="Product description"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Price ($)</span>
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        class="input input-bordered"
                        id="product-price"
                        placeholder="0.00"
                    />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Stock Quantity</span>
                    </label>
                    <input
                        type="number"
                        class="input input-bordered"
                        id="product-stock"
                        placeholder="0"
                    />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Category</span>
                    </label>
                    <select class="select select-bordered" id="product-category">
                        <option value="">Select category</option>
                        <option value="electronics">Electronics</option>
                        <option value="clothing">Clothing</option>
                        <option value="books">Books</option>
                        <option value="home">Home & Garden</option>
                    </select>
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Product Image URL</span>
                </label>
                <input
                    type="url"
                    class="input input-bordered"
                    id="product-image"
                    placeholder="https://example.com/image.jpg"
                />
            </div>

            <div class="form-control">
                <label class="label cursor-pointer">
                    <span class="label-text">Active Product</span>
                    <input
                        type="checkbox"
                        class="toggle toggle-primary"
                        id="product-active"
                        checked
                    />
                </label>
            </div>
        </div>

        <div class="modal-action">
            <button class="btn btn-ghost" onclick="closeAddProductModal()">
                Cancel
            </button>
            <button class="btn btn-primary" onclick="addProduct()">
                Add Product
            </button>
        </div>
    </div>
</dialog>
@endsection

@section('scripts')
<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    // Select all functionality
    document
        .getElementById("select-all")
        .addEventListener("change", function () {
            const checkboxes = document.querySelectorAll(".product-checkbox");
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

    // Product actions
    function editProduct(id) {
        alert(`تعديل المنتج بالرقم: ${id}`);
    }

    function deleteProduct(id) {
        if (confirm("هل أنت متأكد من حذف هذا المنتج؟")) {
            // Here you would typically make an AJAX call to delete the product
            alert(`تم حذف المنتج بالرقم: ${id}`);
        }
    }

    // Modal functions
    function openAddProductModal() {
        document.getElementById("add_product_modal").showModal();
    }

    function closeAddProductModal() {
        document.getElementById("add_product_modal").close();
    }

    // Add product function
    function addProduct() {
        const name = document.getElementById("product-name").value;
        const sku = document.getElementById("product-sku").value;
        const description = document.getElementById(
            "product-description"
        ).value;
        const price = parseFloat(
            document.getElementById("product-price").value
        );
        const stock = parseInt(document.getElementById("product-stock").value);
        const category = document.getElementById("product-category").value;
        const image = document.getElementById("product-image").value;
        const active = document.getElementById("product-active").checked;

        if (!name || !sku || !price || !stock) {
            alert("يرجى ملء جميع الحقول المطلوبة");
            return;
        }

        // Here you would typically make an AJAX call to add the product
        alert(`تم إضافة المنتج: ${name}`);
        closeAddProductModal();

        // Clear form
        document.getElementById("product-name").value = "";
        document.getElementById("product-sku").value = "";
        document.getElementById("product-description").value = "";
        document.getElementById("product-price").value = "";
        document.getElementById("product-stock").value = "";
        document.getElementById("product-category").value = "";
        document.getElementById("product-image").value = "";
        
        // Add event listeners for search and filters
        document.getElementById("search-input").addEventListener("input", function() {
            // Debounce the search to avoid too many requests
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(applyFilters, 500);
        });
        
        document.getElementById("category-filter").addEventListener("change", applyFilters);
        document.getElementById("status-filter").addEventListener("change", applyFilters);

        // Handle Enter key press in search input
        function handleSearchKeyPress(event) {
            if (event.key === 'Enter') {
                applyFilters();
            }
        }

        // Apply filters function
        function applyFilters() {
            const searchTerm = document.getElementById("search-input").value;
            const categoryFilter = document.getElementById("category-filter").value;
            const statusFilter = document.getElementById("status-filter").value;

            // Build URL with parameters
            const url = new URL(window.location);
            url.searchParams.set('search', searchTerm);
            url.searchParams.set('category', categoryFilter);
            url.searchParams.set('status', statusFilter);
            
            // Remove empty parameters
            for (const [key, value] of url.searchParams.entries()) {
                if (!value) {
                    url.searchParams.delete(key);
                }
            }

            // Reload the page with new parameters
            window.location = url;
        }
    }
</script>
@endsection
