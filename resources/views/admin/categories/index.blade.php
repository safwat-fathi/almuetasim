@section('title', 'إدارة الفئات')

<x-layouts.admin>
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        <!-- Main Content -->
        <div class="drawer-content flex flex-col">
            <!-- Page Content -->
            <div class="flex-1 p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold">إدارة الفئات</h1>
                        <p class="text-gray-600">إدارة فئات المنتجات في المتجر</p>
                    </div>

                    <!-- Controls -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="form-control w-full max-w-xs">
                            <input type="text" placeholder="البحث في الفئات..."
                                class="input input-bordered w-full max-w-xs" />
                        </div>
                        <button class="btn btn-primary" onclick="openCreateModal()">
                            <i data-lucide="plus" class="w-4 h-4 ml-2"></i>
                            إضافة فئة جديدة
                        </button>
                    </div>

                    <!-- Categories Table -->
                    <div class="overflow-x-auto bg-white rounded-lg shadow">
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
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="font-medium">{{ $category->name }}</div>
                                        </td>
                                        <td>
                                            <div class="text-sm text-gray-600">{{ $category->description ?? 'لا يوجد' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $category->products->count() }}</span>
                                        </td>
                                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="flex space-x-2">
                                                <button class="btn btn-sm btn-outline"
                                                    onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description ?? '' }}')">
                                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                                </button>
                                                <button class="btn btn-sm btn-error"
                                                    onclick="openDeleteModal({{ $category->id }}, '{{ $category->name }}')">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-8">
                                            <div class="flex flex-col items-center justify-center">
                                                <i data-lucide="folder-open" class="w-16 h-16 text-gray-400 mb-4"></i>
                                                <h3 class="text-lg font-medium">لا توجد فئات</h3>
                                                <p class="text-gray-600">ابدأ بإضافة فئة جديدة</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="p-4">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Category Modal -->
    <div class="modal" id="categoryModal">
        <div class="modal-box max-w-2xl">
            <h3 class="font-bold text-lg" id="modalTitle">إضافة فئة جديدة</h3>
            <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                @method('POST')
                <input type="hidden" id="categoryId" name="id">
                <div class="space-y-4 mt-4">
                    <div>
                        <label for="name" class="label">اسم الفئة <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" class="input input-bordered w-full"
                            required>
                    </div>
                    <div>
                        <label for="description" class="label">الوصف</label>
                        <textarea id="description" name="description" class="textarea textarea-bordered w-full" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="closeModal()">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">تأكيد الحذف</h3>
            <p class="py-4">هل أنت متأكد أنك تريد حذف الفئة "<span id="deleteCategoryName"></span>"؟ لا يمكن التراجع
                عن هذا الإجراء.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-action">
                    <button type="button" class="btn" onclick="closeDeleteModal()">إلغاء</button>
                    <button type="submit" class="btn btn-error">حذف</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

@section('scripts')
    <script>
        // Toggle modal for creating new category
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'إضافة فئة جديدة';
            document.getElementById('categoryForm').action = '{{ route('admin.categories.store') }}';
            document.getElementById('categoryForm').method = 'POST';
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryModal').classList.add('modal-open');
        }

        // Toggle modal for editing category
        function openEditModal(id, name, description) {
            document.getElementById('modalTitle').textContent = 'تعديل الفئة';
            document.getElementById('categoryForm').action = `/admin/categories/${id}`;
            document.getElementById('categoryForm').method = 'POST';
            document.getElementById('categoryId').value = id;

            // Add hidden method field for PUT
            let methodField = document.querySelector('input[name="_method"]');
            if (!methodField) {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                document.getElementById('categoryForm').appendChild(methodInput);
            } else {
                methodField.value = 'PUT';
            }

            document.getElementById('name').value = name;
            document.getElementById('description').value = description || '';
            document.getElementById('categoryModal').classList.add('modal-open');
        }

        // Close modal
        function closeModal() {
            document.getElementById('categoryModal').classList.remove('modal-open');
        }

        // Open delete confirmation modal
        function openDeleteModal(id, name) {
            document.getElementById('deleteCategoryName').textContent = name;
            document.getElementById('deleteForm').action = `/admin/categories/${id}`;
            document.getElementById('deleteModal').classList.add('modal-open');
        }

        // Close delete confirmation modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('modal-open');
        }

        // Close modals when clicking outside the modal box
        document.addEventListener('click', function(event) {
            const categoryModal = document.getElementById('categoryModal');
            const deleteModal = document.getElementById('deleteModal');

            if (event.target === categoryModal) {
                closeModal();
            }

            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>


@endsection
