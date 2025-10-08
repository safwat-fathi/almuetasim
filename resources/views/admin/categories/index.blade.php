<x-layouts.admin title="إدارة الفئات">

    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col">
            <div class="flex-1 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h2 class="card-title text-2xl">إدارة الفئات</h2>
                    <button class="btn btn-primary" x-data @click="$dispatch('open-category-modal')">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        إضافة فئة
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <div class="form-control flex-1">
                        <div class="input-group">
                            <input type="text" 
                                   placeholder="بحث عن فئة..." 
                                   class="input input-bordered flex-1"
                                   id="search-input" 
                                   value="{{ request('search') }}"
                                   onkeypress="handleSearchKeyPress(event)" />
                            <button class="btn btn-square" onclick="applyFilters()">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <select class="select select-bordered w-full sm:w-auto" id="date-filter" onchange="applyFilters()">
                        <option value="">جميع التواريخ</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>
                            اليوم
                        </option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>
                            هذا الأسبوع
                        </option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>
                            هذا الشهر
                        </option>
                    </select>
                </div>

                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>الرقم</th>
                                        <th>الاسم</th>
                                        <th>الوصف</th>
                                        <th>عدد المنتجات</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="categories-table-body">
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="font-bold">{{ $category->name }}</div>
                                            </td>
                                            <td>
                                                <div class="text-sm opacity-70">{{ $category->description ?? 'لا يوجد' }}</div>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $category->products->count() }}</span>
                                            </td>
                                            <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="dropdown dropdown-end">
                                                    <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                                                        <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                                    </div>
                                                    <ul tabindex="0"
                                                        class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                                        <li><a href="{{ route('category.show', $category->slug) }}"><i data-lucide="eye" class="w-4 h-4 mr-2"></i> عرض</a></li>
                                                        <li><a @click="editCategory({{ $category->id }})"><i data-lucide="edit" class="w-4 h-4 mr-2"></i> تعديل</a></li>
                                                        <li><a @click="deleteCategory({{ $category->id }})" class="text-error"><i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> حذف</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <dialog id="add_category_modal" class="modal" 
            x-data="categoryModal" 
            @open-category-modal.window="openModal()" 
            :class="{ 'modal-open': isOpen }">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg mb-4" x-text="isEditing ? 'تعديل الفئة' : 'إضافة فئة جديدة'"></h3>
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute left-2 top-2" @click="closeModal()">✕</button>

            <div class="space-y-4 pt-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">اسم الفئة <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" 
                           class="input input-bordered" 
                           :class="{ 'input-error': errors.name }" 
                           x-model="formData.name" 
                           placeholder="أدخل اسم الفئة" />
                    <label class="label"><span class="label-text-alt text-error" x-show="errors.name" x-text="errors.name"></span></label>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">الوصف</span>
                    </label>
                    <textarea class="textarea textarea-bordered h-24 resize-none w-full" 
                              x-model="formData.description" 
                              placeholder="أدخل وصف الفئة"></textarea>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" @click="closeModal()">إلغاء</button>
                <button type="button" class="btn btn-primary" @click="saveCategory" :disabled="isSubmitting">
                    <span x-show="isSubmitting" class="loading loading-spinner"></span>
                    <span x-text="isSubmitting ? (isEditing ? 'جاري التحديث...' : 'جاري الحفظ...') : (isEditing ? 'تحديث الفئة' : 'أضف الفئة')"></span>
                </button>
            </div>
        </div>

        <form method="dialog" class="modal-backdrop">
            <button @click="closeModal()">إلغاء</button>
        </form>
    </dialog>
</x-layouts.admin>

<x-slot:scripts>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('categoryModal', () => ({
                isOpen: false,
                isSubmitting: false,
                isEditing: false,
                categoryId: null,
                formData: {
                    name: '', description: ''
                },
                errors: {},
                
                openModal() {
                    this.isOpen = true;
                    this.errors = {};
                },
                
                closeModal() {
                    this.isOpen = false;
                },
                
                resetForm() {
                    this.formData = {
                        name: '', description: ''
                    };
                    this.errors = {};
                    this.isSubmitting = false;
                    this.isEditing = false;
                    this.categoryId = null;
                },
                
                async saveCategory() {
                    // Clear any previous errors
                    this.errors = {};
                    
                    // Validate required fields
                    if (!this.formData.name) this.errors.name = 'اسم الفئة مطلوب';
                    
                    // If there are validation errors, don't submit
                    if (Object.keys(this.errors).length > 0) {
                        return;
                    }
                    
                    this.isSubmitting = true;
                    
                    try {
                        // Prepare form data
                        const data = {
                            name: this.formData.name,
                            description: this.formData.description
                        };
                        
                        // Determine the URL and method based on whether we're editing
                        let url, method;
                        if (this.isEditing) {
                            url = `/admin/categories/${this.categoryId}`;
                            method = 'PUT';
                            // Add CSRF token and method override for PUT
                            data._token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            data._method = 'PUT';
                        } else {
                            url = '/admin/categories';
                            method = 'POST';
                        }
                        
                        // Make the actual API request
                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(data)
                        });
                        
                        if (response.ok) {
                            // Success - show success toast
                            this.showToast('تم حفظ الفئة بنجاح!', 'success');
                            this.closeModal();
                            location.reload();
                        } else {
                            // Handle validation errors or other issues
                            const result = await response.json();
                            if (result.message && result.errors) {
                                this.errors = result.errors;
                            } else {
                                this.showToast('حدث خطأ أثناء حفظ الفئة', 'error');
                            }
                        }
                    } catch (error) {
                        console.error('Error saving category:', error);
                        this.showToast('حدث خطأ أثناء حفظ الفئة', 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                },
                
                async editCategory(id) {
                    // Fetch category data
                    try {
                        const response = await fetch(`/admin/categories/${id}`);
                        if (response.ok) {
                            const category = await response.json();
                            this.formData.name = category.name;
                            this.formData.description = category.description;
                            this.categoryId = id;
                            this.isEditing = true;
                            this.openModal();
                        } else {
                            this.showToast('حدث خطأ أثناء جلب بيانات الفئة', 'error');
                        }
                    } catch (error) {
                        console.error('Error fetching category:', error);
                        this.showToast('حدث خطأ أثناء جلب بيانات الفئة', 'error');
                    }
                },
                
                async deleteCategory(id) {
                    if (!confirm('هل أنت متأكد أنك تريد حذف هذه الفئة؟ لا يمكن التراجع عن هذا الإجراء.')) {
                        return;
                    }
                    
                    try {
                        const response = await fetch(`/admin/categories/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            this.showToast('تم حذف الفئة بنجاح!', 'success');
                            location.reload();
                        } else {
                            const result = await response.json();
                            this.showToast(result.message || 'حدث خطأ أثناء حذف الفئة', 'error');
                        }
                    } catch (error) {
                        console.error('Error deleting category:', error);
                        this.showToast('حدث خطأ أثناء حذف الفئة', 'error');
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
        
        function handleSearchKeyPress(event) {
            if (event.key === 'Enter') {
                applyFilters();
            }
        }

        // Apply filters function
        function applyFilters() {
            const searchTerm = document.getElementById("search-input").value;
            const dateFilter = document.getElementById("date-filter").value;

            // Build URL with parameters
            const url = new URL(window.location);
            url.searchParams.set('search', searchTerm);
            url.searchParams.set('date_filter', dateFilter);

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

        document.getElementById("date-filter").addEventListener("change", applyFilters);
    </script>
</x-slot:scripts>