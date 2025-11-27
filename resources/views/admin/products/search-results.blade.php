<x-layouts.admin title="نتائج البحث - المنتجات">

    <div class="min-h-screen bg-base-100">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-200">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i data-lucide="search" class="w-6 h-6 text-white"></i>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-800">نتائج البحث</h1>
                        </div>
                        <p class="text-gray-600 text-lg">
                            @if(request('search'))
                                تم العثور على <span class="font-semibold text-blue-600">{{ $products->total() }}</span> نتيجة للبحث عن: <span class="font-medium text-gray-800">"{{ request('search') }}"</span>
                            @else
                                عرض جميع المنتجات
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-lg border-2 hover:bg-gray-50">
                            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                            العودة لإدارة المخزون
                        </a>
                        <button class="btn btn-primary btn-lg bg-blue-600 hover:bg-blue-700 border-0" x-data @click="$dispatch('open-product-modal')">
                            <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                            إضافة منتج جديد
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search and Filters Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">البحث في المنتجات</span>
                        </label>
                        <div class="input-group">
                            <input type="text" placeholder="اكتب اسم المنتج..." class="input input-bordered input-lg flex-1 focus:input-primary"
                                id="search-input" value="{{ request('search') }}"
                                onkeypress="handleSearchKeyPress(event)" />
                            <button class="btn btn-primary btn-lg" onclick="applyFilters()">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">الفئة</span>
                        </label>
                        <select class="select select-bordered select-lg focus:select-primary" id="category-filter" onchange="applyFilters()">
                            <option value="">جميع الفئات</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">حالة المنتج</span>
                        </label>
                        <select class="select select-bordered select-lg focus:select-primary" id="status-filter" onchange="applyFilters()">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>متاح</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير متاح</option>
                            <option value="low-stock" {{ request('status') == 'low-stock' ? 'selected' : '' }}>مخزون قليل</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200">
                <div class="p-8">
                    @if($products->count() > 0)
                        <!-- Results Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                            @foreach ($products as $product)
                                <div class="card bg-white shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 hover:border-blue-300" data-product-id="{{ $product->id }}" data-product='@json($product)'>
                                    <figure class="px-6 pt-6">
                                        <img src="{{ $product->images ? (Storage::url($product->images[0]) ?? asset('storage/' . $product->images[0])) : 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=300&h=200&fit=crop' }}"
                                             alt="{{ $product->title }}"
                                             class="rounded-xl w-full h-48 object-cover shadow-md"
                                             loading="lazy"
                                              />
                                    </figure>
                                    <div class="card-body p-6">
                                        <h3 class="card-title text-lg font-bold text-gray-800 mb-2">{{ Str::limit($product->title, 30) }}</h3>

                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="badge badge-primary badge-lg">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                            @if ($product->stock > 10)
                                                <span class="badge badge-success badge-lg">متاح</span>
                                            @elseif($product->stock > 0)
                                                <span class="badge badge-warning badge-lg">مخزون قليل</span>
                                            @else
                                                <span class="badge badge-error badge-lg">غير متاح</span>
                                            @endif
                                        </div>

                                        <div class="flex justify-between items-center mb-4">
                                            <div class="text-2xl font-bold text-blue-600">@money($product->price)</div>
                                            <div class="text-sm text-gray-600">الكمية: {{ $product->stock }}</div>
                                        </div>

                                        <div class="card-actions justify-end">
                                            <div class="dropdown dropdown-end">
                                                <div tabindex="0" role="button" class="btn btn-circle btn-ghost btn-sm hover:bg-blue-100">
                                                    <i data-lucide="more-vertical" class="w-5 h-5"></i>
                                                </div>
                                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-xl bg-white rounded-box w-48 border border-gray-200">
                                                    <li><a href="{{ route('product.show', $product->slug) }}" class="text-blue-600 hover:bg-blue-50">
                                                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i> عرض المنتج
                                                    </a></li>
                                                    <li><a onclick="editProduct({{ $product->id }})" class="text-orange-600 hover:bg-orange-50">
                                                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i> تعديل
                                                    </a></li>
                                                    <li><a onclick="deleteProduct({{ $product->id }})" class="text-red-600 hover:bg-red-50">
                                                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> حذف
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center">
                            {{ $products->links() }}
                        </div>
                    @else
                        <!-- No Results -->
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i data-lucide="search-x" class="w-12 h-12 text-blue-500"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">لا توجد نتائج</h3>
                            <p class="text-gray-600 text-lg mb-8">لم نتمكن من العثور على أي منتجات تطابق معايير البحث الخاصة بك</p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">
                                    <i data-lucide="package" class="w-5 h-5 mr-2"></i>
                                    عرض جميع المنتجات
                                </a>
                                <button class="btn btn-outline btn-lg" onclick="clearFilters()">
                                    <i data-lucide="x" class="w-5 h-5 mr-2"></i>
                                    مسح المرشحات
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Modal (same as before) -->
    <dialog id="add_product_modal" class="modal"
        x-data="productModal"
        @open-product-modal.window="openModal()"
        @open-edit-product.window="openForEdit($event.detail)"
        :class="{ 'modal-open': isOpen }">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg mb-4" x-text="isEditing ? 'تعديل المنتج' : 'إضافة منتج جديد'"></h3>
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute left-2 top-2" @click="closeModal()">✕</button>

            <div class="space-y-4 pt-4">
                
                    <div class="form-control w-full">
                        <input type="text" class="input input-bordered w-full" :class="{ 'input-error': errors.name }" x-model="formData.name" placeholder="اسم المنتج" />
                        <label class="label"><span class="label-text-alt text-error" x-show="errors.name" x-text="errors.name"></span></label>
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
                                         class="w-16 h-16 object-cover rounded border"
                                         loading="lazy" />
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
                <button type="button" class="btn btn-primary" @click="isEditing ? updateProduct() : addProduct()" :disabled="isSubmitting">
                    <span x-show="isSubmitting" class="loading loading-spinner"></span>
                    <span x-text="isSubmitting ? 'جاري الحفظ...' : (isEditing ? 'حفظ التعديلات' : 'أضف المنتج')"></span>
                </button>
            </div>
        </div>

        <form method="dialog" class="modal-backdrop">
            <button @click="closeModal()">إلغاء</button>
        </form>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete_product_modal" class="modal" x-data="deleteModal" @open-delete-modal.window="openModal($event.detail)" :class="{ 'modal-open': isOpen }">
        <div class="modal-box">
            <h3 class="font-bold text-lg">تأكيد الحذف</h3>
            <p class="py-4">هل أنت متأكد من حذف هذا المنتج؟ هذا الإجراء لا يمكن التراجع عنه.</p>
            <div class="modal-action">
                <button class="btn" @click="closeModal()">إلغاء</button>
                <button class="btn btn-error" @click="confirmDelete()">حذف</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button @click="closeModal()">إلغاء</button>
        </form>
    </dialog>

    <x-slot:scripts>
        <script>
            // Alpine.js data and functions (same as before)
            document.addEventListener('alpine:init', () => {
                Alpine.data('productModal', () => ({
                    isOpen: false,
                    isSubmitting: false,
                    isEditing: false,
                    imageFiles: [],
                    formData: {
                        name: '', description: '', price: '', stock: '',
                        category_id: '', active: true
                    },
                    errors: {},
                    openModal() {
                        this.isOpen = true;
                        this.errors = {};
                        this.imageFiles = [];
                        this.isEditing = false;
                        this.resetForm();
                    },
                    closeModal() {
                        this.isOpen = false;
                        this.resetForm();
                    },
                    resetForm() {
                        this.formData = {
                            name: '', description: '', price: '', stock: '',
                            category_id: '', active: true
                        };
                        this.imageFiles = [];
                        this.errors = {};
                        this.isSubmitting = false;
                    },
                    updateImageFiles(event) {
                        delete this.errors.image;
                        const files = Array.from(event.target.files);
                        for (const file of files) {
                            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                            if (!validTypes.includes(file.type)) {
                                this.errors.image = 'Please select valid image files (JPEG, JPG, PNG, or WEBP)';
                                return;
                            }
                            const maxSize = 5 * 1024 * 1024;
                            if (file.size > maxSize) {
                                this.errors.image = 'File size exceeds 5MB limit';
                                return;
                            }
                        }
                        this.imageFiles = [...this.imageFiles, ...files];
                    },
                    openForEdit(product) {
                        if (typeof product === 'string') {
                            product = JSON.parse(product);
                        }
                        this.isEditing = true;
                        this.isOpen = true;
                        this.errors = {};
                        this.imageFiles = [];
                        this.formData = {
                            name: product.title || '',
                            description: product.description || '',
                            price: product.price ?? '',
                            stock: product.stock ?? '',
                            category_id: product.category_id ?? '',
                            active: product.active ?? true,
                            id: product.id || null,
                        };
                    },
                    removeImageFile(index) {
                        this.imageFiles.splice(index, 1);
                    },
                    async addProduct() {
                        this.errors = {};
                        if (!this.formData.name) this.errors.name = 'Product name is required';
                        if (!this.formData.price) this.errors.price = 'Price is required';
                        if (!this.formData.stock) this.errors.stock = 'Stock quantity is required';
                        if (!this.formData.category_id) this.errors.category_id = 'Category is required';
                        if (Object.keys(this.errors).length > 0) return;

                        this.isSubmitting = true;
                        try {
                            const formData = new FormData();
                            formData.append('title', this.formData.name);
                            formData.append('description', this.formData.description);
                            formData.append('price', this.formData.price);
                            formData.append('stock', this.formData.stock);
                            formData.append('category_id', this.formData.category_id);
                            formData.append('type', 'product');
                            formData.append('specs', '[]');
                            formData.append('warranty_months', '0');
                            formData.append('is_part', '0');
                            this.imageFiles.forEach((file, index) => {
                                formData.append('images[]', file);
                            });
                            formData.append('active', this.formData.active ? 1 : 0);

                            const response = await fetch('/admin/products', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            const contentType = response.headers.get('content-type') || '';
                            if (response.ok && contentType.includes('application/json')) {
                                const result = await response.json();
                                this.showToast('Product added successfully!', 'success');
                                this.closeModal();
                                location.reload();
                            } else if (!response.ok && contentType.includes('application/json')) {
                                const result = await response.json();
                                if (result.message && result.errors) {
                                    this.errors = result.errors;
                                } else {
                                    this.showToast('An error occurred while adding the product', 'error');
                                }
                            } else {
                                const text = await response.text();
                                console.error('Add product: expected JSON but got:', text);
                                this.showToast('Unexpected server response when adding product', 'error');
                            }
                        } catch (error) {
                            console.error('Error adding product:', error);
                            this.showToast('An error occurred while adding the product', 'error');
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                    async updateProduct() {
                        this.errors = {};
                        if (!this.formData.id) {
                            this.showToast('Invalid product ID', 'error');
                            return;
                        }
                        if (!this.formData.name) this.errors.name = 'Product name is required';
                        if (!this.formData.price) this.errors.price = 'Price is required';
                        if (!this.formData.stock) this.errors.stock = 'Stock quantity is required';
                        if (!this.formData.category_id) this.errors.category_id = 'Category is required';
                        if (Object.keys(this.errors).length > 0) return;

                        this.isSubmitting = true;
                        try {
                            const formData = new FormData();
                            formData.append('title', this.formData.name);
                            formData.append('description', this.formData.description);
                            formData.append('price', this.formData.price);
                            formData.append('stock', this.formData.stock);
                            formData.append('category_id', this.formData.category_id);
                            formData.append('type', 'product');
                            formData.append('specs', '[]');
                            formData.append('warranty_months', '0');
                            formData.append('is_part', '0');
                            formData.append('active', this.formData.active ? 1 : 0);
                            this.imageFiles.forEach((file) => {
                                formData.append('images[]', file);
                            });
                            formData.append('_method', 'PUT');

                            const response = await fetch(`/admin/products/${this.formData.id}`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            const contentType2 = response.headers.get('content-type') || '';
                            if (response.ok && contentType2.includes('application/json')) {
                                const result = await response.json();
                                this.showToast('Product updated successfully!', 'success');
                                this.closeModal();
                                location.reload();
                            } else if (!response.ok && contentType2.includes('application/json')) {
                                const result = await response.json();
                                if (result.errors) this.errors = result.errors;
                                else this.showToast('An error occurred while updating the product', 'error');
                            } else {
                                const text = await response.text();
                                console.error('Update product: expected JSON but got:', text);
                                this.showToast('Unexpected server response when updating product', 'error');
                            }
                        } catch (error) {
                            console.error('Error updating product:', error);
                            this.showToast('An error occurred while updating the product', 'error');
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                    showToast(message, type = 'info') {
                        const existingToast = document.getElementById('toast-container');
                        if (existingToast) existingToast.remove();
                        const toastContainer = document.createElement('div');
                        toastContainer.id = 'toast-container';
                        toastContainer.className = 'toast toast-top toast-center';
                        toastContainer.style.zIndex = '9999';
                        let toastClasses = 'alert ';
                        switch(type) {
                            case 'success': toastClasses += 'alert-success'; break;
                            case 'error': toastClasses += 'alert-error'; break;
                            case 'warning': toastClasses += 'alert-warning'; break;
                            default: toastClasses += 'alert-info'; break;
                        }
                        toastContainer.innerHTML = `<div class="${toastClasses}"><span>${message}</span></div>`;
                        document.body.appendChild(toastContainer);
                        setTimeout(() => { if (toastContainer.parentNode) toastContainer.parentNode.removeChild(toastContainer); }, 5000);
                    }
                }));

                Alpine.data('deleteModal', () => ({
                    isOpen: false,
                    productId: null,
                    openModal(id) {
                        this.productId = id;
                        this.isOpen = true;
                    },
                    closeModal() {
                        this.isOpen = false;
                        this.productId = null;
                    },
                    async confirmDelete() {
                        if (!this.productId) return;
                        await deleteProductConfirmed(this.productId);
                        this.closeModal();
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

                const params = new URLSearchParams();
                if (searchTerm) params.set('search', searchTerm);
                if (categoryFilter) params.set('category', categoryFilter);
                if (statusFilter) params.set('status', statusFilter);

                const fetchUrl = `/admin/products/search-results?${params.toString()}`;
                window.location = fetchUrl;
            }

            // Clear filters function
            function clearFilters() {
                window.location = '/admin/products/search-results';
            }

            // Global helper functions
            function editProduct(id) {
                const productCard = document.querySelector(`[data-product-id="${id}"]`);
                if (!productCard) return;
                const productData = productCard.getAttribute('data-product');
                window.dispatchEvent(new CustomEvent('open-edit-product', { detail: productData }));
            }

            async function deleteProduct(id) {
                window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: id }));
            }

            async function deleteProductConfirmed(id) {
                try {
                    let response = await fetch(`/admin/products/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.status === 405) {
                        response = await fetch(`/admin/products/${id}`, {
                            method: 'POST',
                            body: new URLSearchParams({'_method': 'DELETE'}),
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        });
                    }

                    const contentType = response.headers.get('content-type') || '';
                    if (response.ok && contentType.includes('application/json')) {
                        const result = await response.json();
                        const productCard = document.querySelector(`[data-product-id="${id}"]`);
                        if (productCard) productCard.remove();
                        showToast(result.message || 'تم حذف المنتج بنجاح', 'success');
                    } else if (!response.ok && contentType.includes('application/json')) {
                        const result = await response.json();
                        console.error('Delete failed:', result);
                        showToast(result.message || 'حدث خطأ أثناء حذف المنتج', 'error');
                    } else {
                        const text = await response.text();
                        console.error('Delete product: expected JSON but got:', text);
                        showToast('Unexpected server response when deleting product', 'error');
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    showToast('حدث خطأ أثناء حذف المنتج', 'error');
                }
            }

            function showToast(message, type = 'info') {
                const existingToast = document.getElementById('toast-container');
                if (existingToast) existingToast.remove();
                const toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast toast-top toast-center';
                toastContainer.style.zIndex = '9999';
                let toastClasses = 'alert ';
                switch(type) {
                    case 'success': toastClasses += 'alert-success'; break;
                    case 'error': toastClasses += 'alert-error'; break;
                    case 'warning': toastClasses += 'alert-warning'; break;
                    default: toastClasses += 'alert-info'; break;
                }
                toastContainer.innerHTML = `<div class="${toastClasses}"><span>${message}</span></div>`;
                document.body.appendChild(toastContainer);
                setTimeout(() => { if (toastContainer.parentNode) toastContainer.parentNode.removeChild(toastContainer); }, 5000);
            }
        </script>
    </x-slot:scripts>
</x-layouts.admin>
