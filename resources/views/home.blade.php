@section('description', $settings['store_name'] ?? 'متجر المعتصم لفلاتر المياه')

<x-layouts.app>
    <section class="hero hero-gradient text-white min-h-[calc(45vh-65px)] relative overflow-hidden" aria-label="الHEADER الرئيسي">
        <div class="w-full bg-white/40 backdrop-blur-md flex flex-col items-center justify-center gap-4 md:gap-6 p-8 md:p-12 relative z-10 text-center  ">
            {{-- Hero image optimized with WebP format and explicit dimensions --}}
            <picture>
                <source srcset="{{ asset('images/filter-no-bg.webp') }}" type="image/webp">
                <img
                    src="{{ asset('images/filter-no-bg.png') }}"
                    alt="شعار شركة المعتصم لتقنيات تنقية المياه - توفير فلاتر ومحطات مياه عالية الجودة"
                    width="384"
                    height="384"
                    fetchpriority="high"
                    decoding="async"
                    class="h-40 md:h-56 lg:h-80 xl:h-96 w-auto object-contain drop-shadow-2xl mx-auto select-none" />
            </picture>

            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white/95 drop-shadow-lg leading-tight">
                {{'المعتصم للفلاتر ومحطات المياه' }}
            </h1>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="container mx-auto px-4 py-13" aria-labelledby="featured-categories-heading" role="region">
        <h2 id="featured-categories-heading" class="text-4xl font-bold text-center mb-12 text-[#2d3b61]">خدماتنا المميزة</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" role="list">
            @foreach ($categories as $category)
                <x-featured-category-card slug="{{ $category->slug }}" title="{{ $category->name }}"
                    description="{{ $category->description ?? 'تصنيف منتجات مميزة' }}" />
            @endforeach
        </div>
    </section>

    <!-- Products Section -->
    <section class="container mx-auto px-4 py-16" aria-labelledby="featured-products-heading" role="region">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <h2 id="featured-products-heading" class="text-4xl font-bold mb-4 md:mb-0 text-[#2d3b61]">المنتجات المميزة</h2>
            <div class="flex gap-2">
                <select id="products-sort-select" class="select select-bordered" aria-label="فرز المنتجات">
                    <option value="featured">ترتيب حسب: التميز</option>
                    <option value="price_asc">السعر: من الأقل إلى الأعلى</option>
                    <option value="price_desc">السعر: من الأعلى إلى الأقل</option>
                    <option value="newest">الأحدث أولاً</option>
                </select>
                <div class="join" data-view-toggle-enabled="false" title="ملاحظة: تبديل العرض معطل حالياً - يمكن للفريق تفعيله لاحداً" role="toolbar" aria-label="أوامر عرض المنتجات">
                    <!-- Toggle UI present but disabled. Set data-view-toggle-enabled="true" to enable. -->
                    <!-- <button id="view-grid-btn" class="btn join-item btn-active opacity-60 cursor-not-allowed" aria-pressed="true" title="عرض شبكة (معطل)">
                        <i data-lucide="grid-3x3"></i>
                    </button>
                    <button id="view-list-btn" class="btn join-item opacity-60 cursor-not-allowed" aria-pressed="false" title="عرض قائمة (معطل)">
                        <i data-lucide="list"></i>
                    </button> -->
                </div>
            </div>
        </div>

        <!-- Page-level search removed (now in navbar) -->

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid" role="list" aria-label="قائمة المنتجات">
            <!-- Sample Products -->
            @foreach ($products as $product)
							<x-product-card id="{{ $product->id }}"
								image="{{ $product->images ? $product->images[0] : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop&crop=center' }}"
								slug="{{ $product->slug }}" title="{{ $product->title }}" price="{{ $product->price }}" type="{{ $product->type }}"
								original-price="{{ $product->price * (100 - $product->discount) / 100 }}" on-sale="{{ $product->discount > 0 }}" />
						@endforeach
							</div>
							</section>
							
							<section class="relative overflow-hidden py-16 md:py-20" aria-labelledby="home-gallery-heading" role="region">
								<div
									class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(30,64,175,0.14),_transparent_45%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.12),_transparent_42%)]">
								</div>
								<div class="absolute inset-0 bg-gradient-to-b from-base-100/95 via-base-200/30 to-base-100"></div>
							
								<div class="container mx-auto px-4 relative">
									<div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between mb-10">
										<div>
											<p class="inline-flex items-center gap-2 text-sm font-semibold tracking-wider text-primary/80">
												<span class="inline-block h-2 w-2 rounded-full bg-primary"></span>
												مشاريعنا على أرض الواقع
											</p>
											<h2 id="home-gallery-heading" class="text-4xl font-bold text-[#2d3b61] mt-2">معرض أعمالنا</h2>
											<p class="text-base-content/70 mt-3 max-w-2xl">
												لقطات حقيقية من أعمالنا الأخيرة في التركيب والصيانة بجودة تنفيذ عالية.
											</p>
										</div>
										<a href="{{ route('gallery.index') }}" class="btn btn-primary">
											استعرض كل الأعمال
										</a>
									</div>
							
									@if ($galleryItems->isEmpty())
										<div class="card bg-base-100 border border-dashed border-base-300 shadow-sm">
											<div class="card-body text-center py-14">
												<h3 class="text-2xl font-bold text-[#2d3b61]">المعرض قيد التحديث</h3>
												<p class="text-base-content/70 mt-2">
													سيتم إضافة صور أعمالنا قريبًا. تابعنا للاطلاع على أحدث المشاريع.
												</p>
											</div>
										</div>
									@else
																		@php




																		@endphp

																		<div class="gallery-preview-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5" role="list"
																			aria-label="أحدث أعمالنا">
																			@foreach ($galleryItems as $galleryItem)
																				@php
		$galleryCaption = filled($galleryItem->caption) ? $galleryItem->caption : 'بدون وصف';
																				@endphp
																				<a href="{{ route('gallery.index') }}#gallery-item-{{ $galleryItem->id }}"
																					class="gallery-card group relative block overflow-hidden rounded-3xl border border-slate-300/30 bg-white/95 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
																					aria-label="الانتقال إلى صورة {{ $galleryCaption }}">
																					<img src="{{ asset('storage/' . $galleryItem->image_path) }}" alt="{{ $galleryCaption }}" loading="lazy"
																						decoding="async"
																						class="gallery-media aspect-[16/10] h-auto w-full object-cover transition-transform duration-500 group-hover:scale-105" />
																					<div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/15 to-transparent"></div>
																					<div
																						class="absolute inset-x-4 bottom-4 z-20 gallery-caption-backdrop rounded-xl border border-white/20 bg-black/55 p-3 shadow-xl backdrop-blur-md">
																						<p class="text-sm font-semibold text-white leading-6 drop-shadow-md">{{ $galleryCaption }}</p>
																					</div>
																				</a>
																			@endforeach
																		</div>
									@endif
									</div>
									</section>

    <!-- Contact Form -->
    <x-contact-form />
</x-layouts.app>
