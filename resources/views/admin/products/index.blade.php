<x-layouts.admin title="Products Management">

    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col">
            <div class="flex-1 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h2 class="card-title text-2xl">إدارة المخزون</h2>
                    <button class="btn btn-primary" x-data @click="$dispatch('open-product-modal')">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        إضافة منتج
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <div class="form-control flex-1">
                        <div class="input-group">
                            <input type="text" placeholder="بحث عن منتج..." class="input input-bordered flex-1"
                                id="search-input" value="{{ request('search') }}"
                                onkeypress="handleSearchKeyPress(event)" />
                            <button class="btn btn-square" onclick="applyFilters()">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <select class="select select-bordered w-full sm:w-auto" id="category-filter" onchange="applyFilters()">
                        <option value="">كل الفئات</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select class="select select-bordered w-full sm:w-auto" id="status-filter" onchange="applyFilters()">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>متاح
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير
                            متاح</option>
                        <option value="low-stock" {{ request('status') == 'low-stock' ? 'selected' : '' }}>
                            مخزون قليل</option>
                    </select>
                </div>

                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>المنتج</th>
                                        <th>الفئة</th>
                                        <th>السعر</th>
                                        <th>الكمية</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="products-table-body">
                                    @forelse ($products as $product)
                                        <tr>
                                            <td>
                                                <div class="flex items-center space-x-3">
                                                    <div class="avatar">
                                                        <div class="mask mask-squircle w-12 h-12">
                                                            <img src="{{ $product->images ? (Storage::url($product->images[0]) ?? asset('storage/' . $product->images[0])) : 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=100&h=100&fit=crop' }}"
                                                                alt="{{ $product->title }}" />
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold">{{ $product->title }}</div>
                                                        <div class="text-sm opacity-50">{{ $product->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-ghost">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                            </td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>
                                                @if ($product->stock > 10)
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
                                                    <ul tabindex="0"
                                                        class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                                        <li><a href="{{ route('product.show', $product->slug) }}"><i data-lucide="eye" class="w-4 h-4 mr-2"></i> عرض</a></li>
                                                        <li><a onclick="editProduct({{ $product->id }})"><i data-lucide="edit" class="w-4 h-4 mr-2"></i> تعديل</a></li>
                                                        <li><a onclick="deleteProduct({{ $product->id }})" class="text-error"><i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> حذف</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8">
                                                <p class="text-lg">لا توجد منتجات لعرضها حاليا</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <dialog id="add_product_modal" class="modal" 
            x-data="productModal" 
            @open-product-modal.window="openModal()" 
            :class="{ 'modal-open': isOpen }">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg mb-4">إضافة منتج جديد</h3>
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute left-2 top-2" @click="closeModal()">✕</button>

            <div class="space-y-4 pt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        
                        <input type="text" class="input input-bordered" :class="{ 'input-error': errors.name }" x-model="formData.name" placeholder="اسم المنتج" />
                        <label class="label"><span class="label-text-alt text-error" x-show="errors.name" x-text="errors.name"></span></label>
                    </div>

                </div>

                <div class="form-control flex flex-col w-full">
                    
                    <textarea class="textarea textarea-bordered h-24 resize-none w-full" x-model="formData.description" placeholder="وصف المنتج"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        
                        <input type="number" step="0.01" class="input input-bordered" :class="{ 'input-error': errors.price }" x-model="formData.price" placeholder="السعر" />
                        <label class="label"><span class="label-text-alt text-error" x-show="errors.price" x-text="errors.price"></span></label>
                    </div>
                    <div class="form-control">
                        
                        <input type="number" class="input input-bordered" :class="{ 'input-error': errors.stock }" x-model="formData.stock" placeholder="الكمية" />
                        <label class="label"><span class="label-text-alt text-error" x-show="errors.stock" x-text="errors.stock"></span></label>
                    </div>
                    <div class="form-control">
                        
                        <select class="select select-bordered" x-model="formData.category_id" :class="{ 'select-error': errors.category_id }">
                            <option value="">اختر الفئة</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <label class="label"><span class="label-text-alt text-error" x-show="errors.category_id" x-text="errors.category_id"></span></label>
                    </div>
                </div>

                <div class="form-control flex flex-col">
                    <label class="label">
                        <span class="label-text">صور المنتج</span>
                    </label>
                    <input type="file" 
                           class="file-input file-input-bordered w-full" 
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           multiple
                           @change="updateImageFiles"
                           :class="{ 'file-input-error': errors.image }" />
                    <label class="label">
                        <span class="label-text-alt text-error" x-show="errors.image" x-text="errors.image"></span>
                    </label>
                    <label class="label mt-2">
                        <span class="label-text-alt">الصيغ المدعومة: JPEG, JPG, PNG, WEBP. الحجم المسموح: 5MB لكل صورة</span>
                    </label>
                    <!-- Preview of selected images -->
                    <div class="mt-4" x-show="imageFiles.length > 0">
                        <p class="text-sm font-medium mb-2">معاينة الصور:</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(file, index) in imageFiles" :key="index">
                                <div class="relative">
                                    <img :src="URL.createObjectURL(file)" 
                                         :alt="'Preview ' + index" 
                                         class="w-16 h-16 object-cover rounded border" />
                                    <button type="button" 
                                            class="absolute -top-2 -right-2 btn btn-xs btn-circle btn-error"
                                            @click="removeImageFile(index)">
                                        ×
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text">منتج مفعل</span>
                        <input type="checkbox" class="toggle toggle-primary" x-model="formData.active" :checked="formData.active" />
                    </label>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" @click="closeModal()">إلغاء</button>
                <button type="button" class="btn btn-primary" @click="addProduct" :disabled="isSubmitting">
                    <span x-show="isSubmitting" class="loading loading-spinner"></span>
                    <span x-text="isSubmitting ? 'جاري الحفظ...' : 'أضف المنتج'"></span>
                </button>
            </div>
        </div>

        <form method="dialog" class="modal-backdrop">
            <button @click="closeModal()">إلغاء</button>
        </form>
    </dialog>


    <x-slot:scripts>
        <script>
            // This script block is correct and requires no changes.
            document.addEventListener('alpine:init', () => {
                Alpine.data('productModal', () => ({
                    isOpen: false,
                    isSubmitting: false,
                    imageFile: null, // Add image file property
                    imageFiles: [], // Array to store multiple files
                    formData: {
                        name: '', description: '', price: '', stock: '',
                        category_id: '', active: true
                    },
                    errors: {},
                    openModal() {
                        this.isOpen = true;
                        this.errors = {};
                        this.imageFile = null; // Reset image file when opening
                        this.imageFiles = []; // Reset image files array
                    },
                    closeModal() {
                        this.isOpen = false;
                        // No need to call resetForm() here, the backdrop form closes the dialog naturally.
                    },
                    resetForm() {
                        this.formData = {
                            name: '', description: '', price: '', stock: '',
                            category_id: '', active: true
                        };
                        this.imageFile = null; // Also reset image file
                        this.imageFiles = []; // Reset image files array
                        this.errors = {};
                        this.isSubmitting = false;
                    },
                    updateImageFile(event) {
                        const file = event.target.files[0];
                        
                        // Clear any previous image error
                        delete this.errors.image;
                        
                        if (file) {
                            // Validate file type
                            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                            if (!validTypes.includes(file.type)) {
                                this.errors.image = 'Please select a valid image file (JPEG, JPG, PNG, or WEBP)';
                                this.imageFile = null;
                                return;
                            }
                            
                            // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                            const maxSize = 5 * 1024 * 1024;
                            if (file.size > maxSize) {
                                this.errors.image = 'File size exceeds 5MB limit';
                                this.imageFile = null;
                                return;
                            }
                            
                            this.imageFile = file;
                        }
                    },
                    updateImageFiles(event) {
                        // Clear any previous image error
                        delete this.errors.image;
                        
                        const files = Array.from(event.target.files);
                        
                        // Validate each file
                        for (const file of files) {
                            // Validate file type
                            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                            if (!validTypes.includes(file.type)) {
                                this.errors.image = 'Please select valid image files (JPEG, JPG, PNG, or WEBP)';
                                return;
                            }
                            
                            // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                            const maxSize = 5 * 1024 * 1024;
                            if (file.size > maxSize) {
                                this.errors.image = 'File size exceeds 5MB limit';
                                return;
                            }
                        }
                        
                        // Add files to the array
                        this.imageFiles = [...this.imageFiles, ...files];
                    },
                    removeImageFile(index) {
                        this.imageFiles.splice(index, 1);
                    },
                    async addProduct() {
                        // Clear any previous errors
                        this.errors = {};
                        
                        // Validate required fields
                        if (!this.formData.name) this.errors.name = 'Product name is required';
                        if (!this.formData.price) this.errors.price = 'Price is required';
                        if (!this.formData.stock) this.errors.stock = 'Stock quantity is required';
                        if (!this.formData.category_id) this.errors.category_id = 'Category is required';
                        
                        // If there are validation errors, don't submit
                        if (Object.keys(this.errors).length > 0) {
                            return;
                        }
                        
                        this.isSubmitting = true;
                        
                        try {
                            // Create FormData object to handle file uploads
                            const formData = new FormData();
                            formData.append('title', this.formData.name);  // Backend expects 'title' not 'name'
                            formData.append('description', this.formData.description);
                            formData.append('price', this.formData.price);
                            formData.append('stock', this.formData.stock);
                            formData.append('category_id', this.formData.category_id);
                            formData.append('type', 'product');  // Default type
                            formData.append('specs', '[]');  // Default empty specs
                            formData.append('warranty_months', '0');  // Default warranty
                            formData.append('is_part', '0');  // Default is_part as false
                            
                            // Append multiple images
                            this.imageFiles.forEach((file, index) => {
                                formData.append('images[]', file);
                            });
                            
                            formData.append('active', this.formData.active ? 1 : 0);
                            
                            // Make the actual API request
                            const response = await fetch('/admin/products', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            
                            if (response.ok) {
                                // Success - show success toast
                                this.showToast('Product added successfully!', 'success');
                                this.closeModal();
                                location.reload();
                            } else {
                                // Handle validation errors or other issues
                                const result = await response.json();
                                if (result.message && result.errors) {
                                    this.errors = result.errors;
                                } else {
                                    this.showToast('An error occurred while adding the product', 'error');
                                }
                            }
                        } catch (error) {
                            console.error('Error adding product:', error);
                            this.showToast('An error occurred while adding the product', 'error');
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                    showToast(message, type = 'info') {
                        // Remove any existing toast
                        const existingToast = document.getElementById('toast-container');
                        if (existingToast) {
                            existingToast.remove();
                        }
                        
                        // Create toast container
                        const toastContainer = document.createElement('div');
                        toastContainer.id = 'toast-container';
                        toastContainer.className = 'toast toast-top toast-center';
                        toastContainer.style.zIndex = '9999';
                        
                        // Determine toast classes based on type
                        let toastClasses = 'alert ';
                        switch(type) {
                            case 'success':
                                toastClasses += 'alert-success';
                                break;
                            case 'error':
                                toastClasses += 'alert-error';
                                break;
                            case 'warning':
                                toastClasses += 'alert-warning';
                                break;
                            default:
                                toastClasses += 'alert-info';
                                break;
                        }
                        
                        // Create toast element
                        toastContainer.innerHTML = `
                            <div class="${toastClasses}">
                                <span>${message}</span>
                            </div>
                        `;
                        
                        // Add to page
                        document.body.appendChild(toastContainer);
                        
                        // Remove after 5 seconds
                        setTimeout(() => {
                            if (toastContainer.parentNode) {
                                toastContainer.parentNode.removeChild(toastContainer);
                            }
                        }, 5000);
                    }
                }));
            });

            
            
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

						// Add event listeners for search and filters
            document.getElementById("search-input").addEventListener("input", function() {
                // Debounce the search to avoid too many requests
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(applyFilters, 500);
            });

            document.getElementById("category-filter").addEventListener("change", applyFilters);
            document.getElementById("status-filter").addEventListener("change", applyFilters);
        </script>
    </x-slot:scripts>
</x-layouts.admin>