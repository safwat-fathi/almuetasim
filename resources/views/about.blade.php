<x-layouts.app title="معلومات عنا">
    <div class="min-h-screen bg-base-200 py-12">
        <div class="container mx-auto px-4">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-primary mb-4">{{ $settings['store_name'] ?? 'المعتصم لفلاتر المياه' }}
                </h1>
                <p class="text-xl text-gray-600">{{ $settings['business_type'] ?? 'متجر متخصص في فلاتر المياه' }}</p>
                @if (!empty($settings['opening_date'] ?? ''))
                    <p class="text-lg mt-2">تأسسنا في {{ $settings['opening_date'] ?? '' }}</p>
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
                            @if (!empty($settings['contact_email'] ?? ''))
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <a href="mailto:{{ $settings['contact_email'] ?? '' }}"
                                        class="link link-primary">{{ $settings['contact_email'] ?? '' }}</a>
                                </div>
                            @endif
                            @if (!empty($settings['contact_phone'] ?? ''))
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    <a href="tel:{{ $settings['contact_phone'] ?? '' }}"
                                        class="link link-primary">{{ $settings['contact_phone'] ?? '' }}</a>
                                </div>
                            @endif
                            @if (!empty($settings['contact_address'] ?? ''))
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 mr-3 mt-1 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p>{{ $settings['contact_address'] ?? '' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-2xl mb-4">موقعنا</h3>
                        @if ($settings['location_link'] ?? '')
                            <div class="aspect-video">
                                <iframe src="{{ $embedLink }}" width="100%" height="100%" style="border:0;"
                                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                            <div class="mt-4">
                                <a href="{{ $settings['location_link'] ?? '' }}" target="_blank"
                                    class="btn btn-primary btn-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
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


        </div>
</x-layouts.app>
