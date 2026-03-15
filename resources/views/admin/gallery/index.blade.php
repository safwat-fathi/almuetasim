<x-layouts.admin title="إدارة معرض الأعمال">
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col">
            <div class="flex-1 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h2 class="card-title text-2xl">إدارة معرض الأعمال</h2>
                    <button class="btn btn-primary" x-data @click="$dispatch('open-gallery-item-modal')">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        إضافة صورة
                    </button>
                </div>

                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>الصورة</th>
                                        <th>الوصف</th>
                                        <th>تاريخ الإضافة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($galleryItems as $galleryItem)
                                        <tr>
                                            <td>
                                                <div class="avatar">
                                                    <div class="w-16 h-16 rounded-xl ring ring-base-300 ring-offset-base-100 ring-offset-2">
                                                        <img src="{{ asset('storage/' . $galleryItem->image_path) }}"
                                                            alt="{{ $galleryItem->caption }}"
                                                            loading="lazy"
                                                            decoding="async" />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="font-semibold leading-7 max-w-md">{{ $galleryItem->caption }}</p>
                                            </td>
                                            <td>{{ $galleryItem->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="flex items-center gap-1 whitespace-nowrap">
                                                    <a href="{{ asset('storage/' . $galleryItem->image_path) }}"
                                                        target="_blank"
                                                        rel="noopener"
                                                        class="btn btn-ghost btn-xs"
                                                        aria-label="معاينة الصورة"
                                                        title="معاينة الصورة">
                                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-ghost btn-xs"
                                                        onclick="editGalleryItem({{ $galleryItem->id }})"
                                                        aria-label="تعديل الصورة"
                                                        title="تعديل الصورة">
                                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-ghost btn-xs text-error"
                                                        onclick="deleteGalleryItem({{ $galleryItem->id }})"
                                                        aria-label="حذف الصورة"
                                                        title="حذف الصورة">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-10 text-center text-base-content/70">
                                                لا توجد صور في المعرض حاليًا.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $galleryItems->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <dialog id="gallery_item_modal" class="modal"
        x-data="galleryItemModal"
        @open-gallery-item-modal.window="openModal()"
        @open-edit-gallery-item.window="openForEdit($event.detail)"
        :class="{ 'modal-open': isOpen }">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg mb-2" x-text="isEditing ? 'تعديل عنصر المعرض' : 'إضافة عنصر جديد'"></h3>
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute left-2 top-2" @click="closeModal()">✕</button>

            <div class="space-y-4 pt-4">
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">الوصف</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" x-model="formData.caption"
                        :class="{ 'input-error': errors.caption }" placeholder="اكتب وصف الصورة" />
                    <label class="label">
                        <span class="label-text-alt text-error" x-show="errors.caption" x-text="errors.caption"></span>
                    </label>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text" x-text="isEditing ? 'استبدال الصورة (اختياري)' : 'الصورة'"></span>
                    </label>
                    <input type="file" class="file-input file-input-bordered w-full"
                        accept="image/jpeg,image/jpg,image/png,image/webp"
                        @change="setImageFile"
                        :class="{ 'file-input-error': errors.image }" />
                    <label class="label">
                        <span class="label-text-alt text-error" x-show="errors.image" x-text="errors.image"></span>
                    </label>
                    <label class="label">
                        <span class="label-text-alt">الصيغ المدعومة: JPEG, JPG, PNG, WEBP (حد أقصى 5MB)</span>
                    </label>
                </div>

                <div class="rounded-xl border border-base-300 p-3" x-show="previewUrl">
                    <p class="text-sm font-semibold mb-2">معاينة الصورة</p>
                    <img :src="previewUrl" alt="معاينة" class="w-full max-h-72 object-cover rounded-lg" />
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" @click="closeModal()">إلغاء</button>
                <button type="button" class="btn btn-primary" @click="saveItem()" :disabled="isSubmitting">
                    <span x-show="isSubmitting" class="loading loading-spinner"></span>
                    <span x-text="isSubmitting ? 'جارٍ الحفظ...' : (isEditing ? 'حفظ التعديلات' : 'إضافة')"></span>
                </button>
            </div>
        </div>

        <form method="dialog" class="modal-backdrop">
            <button @click="closeModal()">إغلاق</button>
        </form>
    </dialog>

    <dialog id="delete_gallery_item_modal" class="modal"
        x-data="deleteGalleryItemModal"
        @open-delete-gallery-item.window="openModal($event.detail)"
        :class="{ 'modal-open': isOpen }">
        <div class="modal-box">
            <h3 class="font-bold text-lg">تأكيد الحذف</h3>
            <p class="py-4">هل أنت متأكد من حذف هذا العنصر؟ لا يمكن التراجع عن هذا الإجراء.</p>
            <div class="modal-action">
                <button class="btn" @click="closeModal()">إلغاء</button>
                <button class="btn btn-error" @click="confirmDelete()" :disabled="isSubmitting">
                    <span x-show="isSubmitting" class="loading loading-spinner"></span>
                    <span x-text="isSubmitting ? 'جارٍ الحذف...' : 'حذف'"></span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button @click="closeModal()">إغلاق</button>
        </form>
    </dialog>

    <x-slot:scripts>
        <script>
            function editGalleryItem(id) {
                window.dispatchEvent(new CustomEvent('open-edit-gallery-item', { detail: id }));
            }

            function deleteGalleryItem(id) {
                window.dispatchEvent(new CustomEvent('open-delete-gallery-item', { detail: id }));
            }

            document.addEventListener('alpine:init', () => {
                Alpine.data('galleryItemModal', () => ({
                    isOpen: false,
                    isSubmitting: false,
                    isEditing: false,
                    itemId: null,
                    imageFile: null,
                    previewUrl: '',
                    formData: {
                        caption: '',
                    },
                    errors: {},

                    openModal() {
                        this.resetForm();
                        this.isOpen = true;
                    },

                    closeModal() {
                        this.isOpen = false;
                        this.resetForm();
                    },

                    resetForm() {
                        this.isSubmitting = false;
                        this.isEditing = false;
                        this.itemId = null;
                        this.imageFile = null;
                        this.previewUrl = '';
                        this.formData = { caption: '' };
                        this.errors = {};
                    },

                    setImageFile(event) {
                        this.errors.image = null;
                        const file = event.target.files[0];
                        this.imageFile = file || null;

                        if (!file) {
                            if (this.isEditing && this.previewUrl) {
                                return;
                            }
                            this.previewUrl = '';
                            return;
                        }

                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            this.errors.image = 'صيغة الصورة غير مدعومة.';
                            this.imageFile = null;
                            this.previewUrl = '';
                            return;
                        }

                        const maxSize = 5 * 1024 * 1024;
                        if (file.size > maxSize) {
                            this.errors.image = 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت.';
                            this.imageFile = null;
                            this.previewUrl = '';
                            return;
                        }

                        this.previewUrl = URL.createObjectURL(file);
                    },

                    async openForEdit(id) {
                        this.resetForm();
                        this.isOpen = true;
                        this.isEditing = true;
                        this.itemId = id;

                        const response = await fetch(`/admin/gallery/${id}`, {
                            headers: { 'Accept': 'application/json' },
                        });

                        if (!response.ok) {
                            this.closeModal();
                            return;
                        }

                        const item = await response.json();
                        this.formData.caption = item.caption;
                        this.previewUrl = `/storage/${item.image_path}`;
                    },

                    async saveItem() {
                        this.errors = {};

                        if (!this.formData.caption) {
                            this.errors.caption = 'حقل الوصف مطلوب.';
                        }

                        if (!this.isEditing && !this.imageFile) {
                            this.errors.image = 'الصورة مطلوبة.';
                        }

                        if (Object.keys(this.errors).length > 0) {
                            return;
                        }

                        this.isSubmitting = true;

                        try {
                            const data = new FormData();
                            data.append('caption', this.formData.caption);

                            if (this.imageFile) {
                                data.append('image', this.imageFile);
                            }

                            let url = '/admin/gallery';
                            if (this.isEditing) {
                                url = `/admin/gallery/${this.itemId}`;
                                data.append('_method', 'PUT');
                            }

                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                                body: data,
                            });

                            if (response.ok) {
                                location.reload();
                                return;
                            }

                            if (response.status === 422) {
                                const result = await response.json();
                                if (result.errors) {
                                    Object.keys(result.errors).forEach((key) => {
                                        this.errors[key] = result.errors[key][0];
                                    });
                                }
                            }
                        } catch (error) {
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                }));

                Alpine.data('deleteGalleryItemModal', () => ({
                    isOpen: false,
                    isSubmitting: false,
                    itemId: null,

                    openModal(id) {
                        this.isOpen = true;
                        this.isSubmitting = false;
                        this.itemId = id;
                    },

                    closeModal() {
                        this.isOpen = false;
                        this.isSubmitting = false;
                        this.itemId = null;
                    },

                    async confirmDelete() {
                        if (!this.itemId) {
                            return;
                        }

                        this.isSubmitting = true;

                        try {
                            const response = await fetch(`/admin/gallery/${this.itemId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                            });

                            if (response.ok) {
                                location.reload();
                                return;
                            }
                        } catch (error) {
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                }));
            });
        </script>
    </x-slot:scripts>
</x-layouts.admin>
