<x-layouts.app title="معلومات عنا">
    <div class="min-h-screen bg-base-200 py-12">
        <div class="container mx-auto px-4">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-primary mb-4">{{ $settings['store_name'] ?? 'المعتصم لفلاتر المياه' }}</h1>
                <p class="text-xl text-gray-600">{{ $settings['business_type'] ?? 'متجر متخصص في فلاتر المياه' }}</p>
                @if(!empty($settings['opening_date']))
                    <p class="text-lg mt-2">تأسسنا في {{ $settings['opening_date'] }}</p>
                @endif
            </div>

            <!-- About Content -->
            <div class="card bg-base-100 shadow-xl mb-12">
                <div class="card-body">
                    <h2 class="card-title text-3xl mb-6">من نحن</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($settings['about_us_content'] ?? 'محتوى صفحة من نحن غير محدد بعد.')) !!}
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid md:grid-cols-2 gap-8 mb-12">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-2xl mb-4">معلومات التواصل</h3>
                        <div class="space-y-4">
                            @if(!empty($settings['contact_email']))
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <a href="mailto:{{ $settings['contact_email'] }}" class="link link-primary">{{ $settings['contact_email'] }}</a>
                                </div>
                            @endif
                            @if(!empty($settings['contact_phone']))
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <a href="tel:{{ $settings['contact_phone'] }}" class="link link-primary">{{ $settings['contact_phone'] }}</a>
                                </div>
                            @endif
                            @if(!empty($settings['contact_address']))
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 mr-3 mt-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p>{{ $settings['contact_address'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-2xl mb-4">موقعنا</h3>
                        @if($settings['location_link'])
                            <div class="aspect-video">
                                <iframe
                                    src="{{ $embedLink }}"
                                    width="100%"
                                    height="100%"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                            <div class="mt-4">
                                <a href="{{ $settings['location_link'] }}" target="_blank" class="btn btn-primary btn-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    عرض على الخريطة
                                </a>
                            </div>
                        @else
                            <p class="text-gray-500">لم يتم تحديد الموقع بعد.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body text-center">
                    <h3 class="card-title text-2xl mb-6">تابعنا على وسائل التواصل</h3>
                    <div class="flex justify-center space-x-4 rtl:space-x-reverse">
                        @if(!empty($settings['social_facebook']))
                            <a href="{{ $settings['social_facebook'] }}" target="_blank" class="btn btn-circle btn-outline">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                        @endif
                        @if(!empty($settings['social_twitter']))
                            <a href="{{ $settings['social_twitter'] }}" target="_blank" class="btn btn-circle btn-outline">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                        @endif
                        @if(!empty($settings['social_instagram']))
                            <a href="{{ $settings['social_instagram'] }}" target="_blank" class="btn btn-circle btn-outline">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.948 16.126c-2.27 0-4.108-1.838-4.108-4.109 0-2.27 1.837-4.108 4.108-4.108 2.27 0 4.108 1.837 4.108 4.108 0 2.271-1.838 4.109-4.108 4.109zm7.982-8.681h-2.956v9.124h2.956V7.445z"/>
                                </svg>
                            </a>
                        @endif
                        @if(!empty($settings['social_linkedin']))
                            <a href="{{ $settings['social_linkedin'] }}" target="_blank" class="btn btn-circle btn-outline">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
