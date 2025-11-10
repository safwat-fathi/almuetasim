<x-layouts.admin title="إعدادات الموقع">
    <div class="p-6">
        <h2 class="card-title text-2xl mb-6">إعدادات الموقع</h2>

        <div class="card bg-base-100 shadow-lg">
            <div class="card-body">
                <!-- Flash messages -->
                @if(session('success'))
                    <div class="alert alert-success" id="success-message">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-error" id="error-message">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form id="settings-form" action="{{ route('admin.settings.store') }}" method="POST">
                    @csrf
                    <div class="space-y-8">

                        <div>
                            <h3 class="text-lg font-semibold mb-4 pb-2">روابط التواصل الاجتماعي</h3>
                            <div class="space-y-4 grid grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text">فيسبوك</span>
                                    </label>
                                    <input type="text" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" class="input input-bordered w-full" placeholder="https://facebook.com/username">
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text">تويتر</span>
                                    </label>
                                    <input type="text" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" class="input input-bordered w-full" placeholder="https://twitter.com/username">
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text">انستغرام</span>
                                    </label>
                                    <input type="text" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" class="input input-bordered w-full" placeholder="https://instagram.com/username">
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text">لينكد ان</span>
                                    </label>
                                    <input type="text" name="social_linkedin" value="{{ old('social_linkedin', $settings['social_linkedin'] ?? '') }}" class="input input-bordered w-full" placeholder="https://linkedin.com/in/username">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4 pb-2">معلومات التواصل</h3>
                            <div class="space-y-4">
																<div class="flex gap-4">
																	<div class="form-control w-full">
																			<label class="label">
																					<span class="label-text">البريد الإلكتروني</span>
																			</label>
																			<input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="input input-bordered w-full" placeholder="example@domain.com">
																	</div>
																	<div class="form-control w-full">
																			<label class="label">
																					<span class="label-text">الهاتف</span>
																			</label>
																			<input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" class="input input-bordered w-full" placeholder="+1234567890">
																	</div>

																</div>
                                <div class="form-control flex flex-col w-full">
                                    <label class="label">
                                        <span class="label-text">العنوان</span>
                                    </label>
                                    <textarea name="contact_address" class="w-full textarea textarea-bordered h-24 resize-none">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4 pb-2">معلومات المتجر</h3>
                            <div class="space-y-4 grid grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text">اسم المتجر</span>
                                    </label>
                                    <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}" class="input input-bordered w-full" placeholder="اسم المتجر">
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text">نوع العمل</span>
                                    </label>
                                    <input type="text" name="business_type" value="{{ old('business_type', $settings['business_type'] ?? '') }}" class="input input-bordered w-full" placeholder="مثال: متجر متخصص في فلاتر المياه">
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text">تاريخ الافتتاح</span>
                                    </label>
                                    <input type="text" name="opening_date" value="{{ old('opening_date', $settings['opening_date'] ?? '') }}" class="input input-bordered w-full" placeholder="مثال: 2020">
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text">رابط الموقع (خريطة)</span>
                                    </label>
                                    <input type="url" name="location_link" value="{{ old('location_link', $settings['location_link'] ?? '') }}" class="input input-bordered w-full" placeholder="https://maps.google.com/...">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4 pb-2">من نحن</h3>
                            <div class="form-control flex flex-col">
                                <label class="label">
                                    <span class="label-text">محتوى صفحة من نحن</span>
                                </label>
                                <textarea name="about_us_content" class="w-full textarea textarea-bordered h-48 resize-none">{{ old('about_us_content', $settings['about_us_content'] ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="btn btn-primary" id="submit-btn">حفظ الإعدادات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('settings-form');
                const submitBtn = document.getElementById('submit-btn');
                
                // Simple toast function similar to other admin pages
                function showToast(message, type = 'info') {
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
                
                // Handle form submission
                form.addEventListener('submit', function(e) {
                    // Show loading state on submit button
                    submitBtn.innerHTML = '<span class="loading loading-spinner loading-xs mr-2"></span> جاري الحفظ...';
                    submitBtn.disabled = true;
                    
                    // If there are flash messages on the page, show them as toasts too
                    const successMsg = document.getElementById('success-message');
                    if (successMsg) {
                        const message = successMsg.textContent.trim();
                        if (message) {
                            showToast(message, 'success');
                            successMsg.remove();
                        }
                    }
                    
                    const errorMsg = document.getElementById('error-message');
                    if (errorMsg) {
                        const message = errorMsg.textContent.trim();
                        if (message) {
                            showToast(message, 'error');
                            errorMsg.remove();
                        }
                    }
                });
                
                // If there's a success message in the DOM (from a redirect), show it as a toast
                const successMsg = document.getElementById('success-message');
                if (successMsg) {
                    const message = successMsg.textContent.trim();
                    if (message) {
                        showToast(message, 'success');
                    }
                }
                
                // If there's an error message in the DOM (from a redirect), show it as a toast
                const errorMsg = document.getElementById('error-message');
                if (errorMsg) {
                    const message = errorMsg.textContent.trim();
                    if (message) {
                        showToast(message, 'error');
                    }
                }
            });
        </script>
    </x-slot:scripts>
</x-layouts.admin>
