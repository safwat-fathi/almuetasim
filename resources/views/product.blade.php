@php
    use Illuminate\Support\Str;
@endphp

@section('title', $product->title)

@section('description', $product->description)

<x-layouts.app>
    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 py-4">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="/" class="text-primary hover:underline">الرئيسية</a></li>
                @if($product->category)
                <li><a href="{{ route('category.show', $product->category->slug) }}"
                        class="text-primary hover:underline">{{ $product->category->name }}</a></li>
                @endif
                <li>{{ $product->title }}</li>
            </ul>
        </div>
    </div>

    @php
        $images = $product->images ?? [];
        // Convert stored images to proper URLs if needed
        $processedImages = [];
        foreach ($images as $image) {
            if (Str::startsWith($image, ['http://', 'https://', '/'])) {
                $processedImages[] = $image;
            } else {
                $processedImages[] = Storage::url($image);
            }
        }
        $mainImage = !empty($processedImages)
            ? $processedImages[0]
            : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop&crop=center';
    @endphp

    <!-- Product Details -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4" x-data="productGallery({{ json_encode($processedImages) }})">
                <div class="relative overflow-hidden rounded-lg bg-base-200 aspect-square select-none"
                     @mouseenter="zoomed = true" @mouseleave="zoomed = false"
                     @mousemove="onMove($event)">
                    <img :src="activeImage" alt="{{ $product->title }}"
                        :style="zoomStyle"
                        :class="zoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'"
                        class="w-full h-full object-cover transition-transform duration-200 ease-out" />
                    @if (!empty($product->discount) && $product->discount > 0)
                        <div class="badge badge-secondary absolute top-4 left-4">
                            {{ $product->discount }}% OFF
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Images -->
                @if (count($processedImages) > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach ($processedImages as $index => $image)
                            <button type="button"
                                    :class="activeIndex === {{ $index }} ? 'ring-2 ring-primary' : ''"
                                    class="aspect-square rounded-lg overflow-hidden cursor-pointer focus:outline-none"
                                    @click="setActive({{ $index }})">
                                <img src="{{ $image }}" alt="View {{ $index + 1 }}" class="w-full h-full object-cover" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold mb-2">{{ $product->title }}</h1>
                    <p class="text-base-content/70 mb-4">{{ $product->description }}</p>

                    <!-- Rating -->
                    <div class="flex items-center gap-2 mb-4">
                        <div class="rating rating-sm">
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                        </div>
                        {{-- <span class="text-sm text-base-content/70">4.8 (247 reviews)</span> --}}
                        @if ($product->stock > 0)
                            <span class="text-success text-sm font-medium">✓ متوفر</span>
                        @else
                            <span class="text-error text-sm font-medium">غير متوفر</span>
                        @endif
                    </div>

                    <!-- Price -->
                    @php
                        $discount = (int) ($product->discount ?? 0);
                        $finalPrice = $discount > 0 ? round(($product->price * (100 - $discount)) / 100, 2) : $product->price;
                    @endphp
                    <div class="flex items-center gap-3 mb-6">
                        <span class="text-3xl font-bold text-primary">@money($finalPrice)</span>
                        @if ($discount > 0)
                            <span class="text-xl line-through text-base-content/50">@money($product->price)</span>
                            <span class="badge badge-secondary">خصم {{ $discount }}%</span>
                        @endif
                    </div>
                </div>

                <!-- Quantity -->
                {{-- <div>
                    <h3 class="font-semibold mb-3">Quantity</h3>
                    <div class="flex items-center gap-3">
                        <div class="join">
                            <button class="btn join-item btn-sm" onclick="decreaseQty()">-</button>
                            <input type="number" id="quantity" value="1" min="1"
                                class="input input-bordered join-item w-16 text-center input-sm" />
                            <button class="btn join-item btn-sm" onclick="increaseQty()">+</button>
                        </div>
                        @if ($product->stock > 0)
                            <span class="text-sm text-base-content/70">Only {{ $product->stock }} left in stock!</span>
                        @endif
                    </div>
                </div> --}}

                <!-- Buttons -->
                <div class="space-y-3" x-data="{ 
                    wishlistLoading: false,
                    shareLoading: false,
                    inWishlist: {{ $inWishlist ? 'true' : 'false' }},
                    showToast(message, type = 'info') {
                        const toast = document.createElement('div');
                        toast.className = 'toast toast-bottom toast-start z-50';

                        toast.innerHTML = `
                            <div class='alert alert-${type}'>
                                <span>${message}</span>
                            </div>
                        `;
                        document.body.appendChild(toast);
                        
                        setTimeout(() => {
                            toast.remove();
                        }, 3000);
                    },
                    toggleWishlist() {
                        this.wishlistLoading = true;
                        const url = this.inWishlist 
                            ? '{{ route('wishlist.remove', $product->id) }}' 
                            : '{{ route('wishlist.add', $product->id) }}';
                            
                        fetch(url, { 
                            method: 'POST', 
                            headers: { 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            } 
                        })
                        .then(response => response.json())
                        .then(data => { 
                            this.wishlistLoading = false; 
                            if (data.success) {
                                this.inWishlist = !this.inWishlist;
                                
                                // Dispatch custom event to update navbar with count AND HTML
                                document.dispatchEvent(new CustomEvent('wishlistUpdated', { 
                                    detail: { 
                                        count: data.count,
                                        dropdownHtml: data.dropdownHtml
                                    } 
                                }));
                                
                                this.showToast(
                                    this.inWishlist ? 'تمت الإضافة إلى المفضلة!' : 'تمت الإزالة من المفضلة!', 
                                    'success'
                                );
                            }
                        })
                        .catch(error => { 
                            this.wishlistLoading = false; 
                            this.showToast('حدث خطأ ما', 'error');
                            console.error(error);
                        });
                    }
                }">

                    <button class="btn btn-primary btn-lg w-full add-to-cart-btn"
                        data-product-id="{{ $product->id }}">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        إضافة للسلة
                    </button>

                    <div class="grid grid-cols-2 gap-3">
                        <button class="btn btn-outline btn-lg" 
                            :class="{ 'loading': wishlistLoading, 'btn-error text-white': inWishlist, 'btn-outline': !inWishlist }"
                            x-on:click="toggleWishlist()">
                            <i data-lucide="heart" class="w-5 h-5" :class="{ 'fill-current': inWishlist }"></i>
                            <span x-text="inWishlist ? 'إزالة من المفضلة' : 'إضافة للمفضلة'"></span>
                        </button>

                        <button class="btn btn-outline btn-lg"
                            :class="{ 'loading': shareLoading }"
                            x-on:click="shareLoading = true; navigator.clipboard.writeText(window.location.href).then(() => { 
                                shareLoading = false; 
                                showToast('تم نسخ الرابط!', 'success');
                            }).catch(err => { 
                                shareLoading = false; 
                                showToast('Failed to copy URL', 'error');
                            });">
                            <i data-lucide="share-2" class="w-5 h-5"></i>
														شارك
                        </button>
                    </div>
                </div>

                <!-- Product Type and Features -->
                <div class="border-t pt-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="text-lg font-semibold">
                            @if($product->type === 'service')
                                <span class="badge badge-info">خدمة</span>
                            @else
                                <span class="badge badge-success">منتج</span>
                            @endif
                        </div>
                        @if($product->is_part)
                            <div class="text-sm">
                                <span class="badge badge-warning">قطعة غيار</span>
                            </div>
                        @endif
                    </div>
                    <h3 class="font-semibold mb-3">الميزات الرئيسية</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                            <span class="text-sm">High-quality materials</span>
                        </li>
                        @if($product->warranty_months > 0)
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                            <span class="text-sm">{{ $product->warranty_months }} months warranty</span>
                        </li>
                        @endif
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                            <span class="text-sm">Easy to use</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                            <span class="text-sm">Reliable performance</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="mt-16">
            <div class="tabs tabs-bordered mb-8" x-data="{ activeTab: 'description' }">
                <button type="button" class="tab" :class="{ 'tab-active': activeTab === 'description' }" @click="activeTab = 'description'">الوصف</button>
                <button type="button" class="tab" :class="{ 'tab-active': activeTab === 'specifications' }" @click="activeTab = 'specifications'">المواصفات</button>
                <button type="button" class="tab" :class="{ 'tab-active': activeTab === 'reviews' }" @click="activeTab = 'reviews'">التقييمات</button>
                <button type="button" class="tab" :class="{ 'tab-active': activeTab === 'shipping' }" @click="activeTab = 'shipping'">الشحن والإرجاع</button>
            </div>

            <!-- Description Tab -->
            <div x-show="activeTab === 'description'" class="tab-content">
                <div class="prose max-w-none">
                    <p class="text-lg mb-4">{{ $product->description }}</p>

                    <h3>تفاصيل المنتج:</h3>
                    <ul>
                        <li>الفئة: {{ $product->category->name }}</li>
                        @if ($product->is_part)
                        <li>هذا قطعة غيار/إكسسوار</li>
                        @else
                        <li>هذا منتج كامل</li>
                        @endif
                        @if ($product->warranty_months > 0)
                        <li>الضمان: {{ $product->warranty_months }} أشهر</li>
                        @endif
                    </ul>

                    <h3>مناسب لـ:</h3>
                    <ul>
                        <li>العملاء الذين يبحثون عن {{ $product->category->name }} عالي الجودة</li>
                        <li>أولئك الذين يقدرون الموثوقية والأداء</li>
                        <li>أي شخص يبحث عن قيمة ممتازة مقابل المال</li>
                    </ul>
                </div>
            </div>

            <!-- Specifications Tab -->
            <div x-show="activeTab === 'specifications'" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-lg mb-4">مواصفات المنتج</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between border-b pb-1">
                                <span class="font-medium">الفئة:</span>
                                <span>{{ $product->category->name }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-1">
                                <span class="font-medium">النوع:</span>
                                <span>{{ $product->is_part ? 'قطعة غيار' : 'منتج كامل' }}</span>
                            </div>
                            @if ($product->warranty_months > 0)
                            <div class="flex justify-between border-b pb-1">
                                <span class="font-medium">الضمان:</span>
                                <span>{{ $product->warranty_months }} أشهر</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-4">التفاصيل الفنية</h3>
                        <div class="space-y-2">
                            @if (isset($product->specs) && is_array($product->specs))
                                @foreach ($product->specs as $key => $value)
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">{{ ucfirst($key) }}:</span>
                                    <span>{{ $value }}</span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-base-content/70">لا توجد مواصفات إضافية متاحة.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div>
                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold mb-2">4.8</div>
                            <div class="rating rating-sm mb-2">
                                <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                            </div>
                            <div class="text-sm text-base-content/70">247 تقييم</div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">5★</span>
                                <progress class="progress progress-primary w-24" value="85" max="100"></progress>
                                <span class="text-sm">85%</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">4★</span>
                                <progress class="progress progress-primary w-24" value="12" max="100"></progress>
                                <span class="text-sm">12%</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">3★</span>
                                <progress class="progress progress-primary w-24" value="2" max="100"></progress>
                                <span class="text-sm">2%</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">2★</span>
                                <progress class="progress progress-primary w-24" value="1" max="100"></progress>
                                <span class="text-sm">1%</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">1★</span>
                                <progress class="progress progress-primary w-24" value="0" max="100"></progress>
                                <span class="text-sm">0%</span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2 space-y-6">
                        <!-- Review 1 -->
                        <div class="border-b pb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="rating rating-sm">
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                </div>
                                <span class="font-medium">سارة جونسون</span>
                                <span class="text-sm text-base-content/70">منذ يومين</span>
                            </div>
                            <p class="text-sm mb-2"><strong>جودة مذهلة!</strong></p>
                            <p class="text-sm text-base-content/70">هذا {{ $product->title }} تجاوز توقعاتي. جودة البناء ممتازة ويعمل بشكل مثالي. أوصي به بشدة!</p>
                        </div>

                        <!-- Review 2 -->
                        <div class="border-b pb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="rating rating-sm">
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" disabled />
                                </div>
                                <span class="font-medium">مايك تشين</span>
                                <span class="text-sm text-base-content/70">منذ أسبوع</span>
                            </div>
                            <p class="text-sm mb-2"><strong>قيمة ممتازة مقابل المال</strong></p>
                            <p class="text-sm text-base-content/70">{{ $product->category->name }} مثالي. يعمل تماماً كما هو موصوف. السعر معقول جداً للجودة التي تحصل عليها.</p>
                        </div>

                        <!-- Review 3 -->
                        <div class="border-b pb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="rating rating-sm">
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                    <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                                </div>
                                <span class="font-medium">إميلي رودريغيز</span>
                                <span class="text-sm text-base-content/70">منذ أسبوعين</span>
                            </div>
                            <p class="text-sm mb-2"><strong>أفضل شراء هذا العام!</strong></p>
                            <p class="text-sm text-base-content/70">الجودة ممتازة ووصل بسرعة. سأشتري بالتأكيد من هذا المتجر مرة أخرى.</p>
                        </div>

                        <button class="btn btn-outline w-full">تحميل المزيد من التقييمات</button>
                    </div>
                </div>
            </div>

            <!-- Shipping Tab -->
            <div x-show="activeTab === 'shipping'" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-lg mb-4">خيارات الشحن</h3>
                        <div class="space-y-4">
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">التوصيل القياسي</span>
                                    <span class="text-success font-bold">مجاني</span>
                                </div>
                                <p class="text-sm text-base-content/70">5-7 أيام عمل</p>
                            </div>
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">التوصيل السريع</span>
                                    <span class="font-bold">@money(99.99)</span>
                                </div>
                                <p class="text-sm text-base-content/70">2-3 أيام عمل</p>
                            </div>
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">التوصيل في اليوم التالي</span>
                                    <span class="font-bold">@money(199.99)</span>
                                </div>
                                <p class="text-sm text-base-content/70">اطلب قبل الساعة 2 مساءً للتوصيل في اليوم التالي</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-4">سياسة الإرجاع</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <i data-lucide="shield-check" class="w-5 h-5 text-success mt-1"></i>
                                <div>
                                    <h4 class="font-medium">إرجاع خلال 30 يوم</h4>
                                    <p class="text-sm text-base-content/70">أعد المنتج خلال 30 يوم لاسترداد كامل</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i data-lucide="truck" class="w-5 h-5 text-success mt-1"></i>
                                <div>
                                    <h4 class="font-medium">شحن إرجاع مجاني</h4>
                                    <p class="text-sm text-base-content/70">سنغطي تكاليف شحن الإرجاع</p>
                                </div>
                            </div>
                            @if ($product->warranty_months > 0)
                            <div class="flex items-start gap-3">
                                <i data-lucide="headphones" class="w-5 h-5 text-success mt-1"></i>
                                <div>
                                    <h4 class="font-medium">ضمان {{ $product->warranty_months }} شهر</h4>
                                    <p class="text-sm text-base-content/70">ضمان الشركة المصنعة الكامل مشمول</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Related Products -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold mb-8">منتجات ذات صلة</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if($relatedProducts && $relatedProducts->count() > 0)
                        @foreach($relatedProducts as $relatedProduct)
                            @php
                                $relatedProductImage = null;
                                if (isset($relatedProduct->images[0])) {
                                    $image = $relatedProduct->images[0];
                                    if (Str::startsWith($image, ['http://', 'https://', '/'])) {
                                        $relatedProductImage = $image;
                                    } else {
                                        $relatedProductImage = Storage::url($image);
                                    }
                                } else {
                                    $relatedProductImage = 'https://placehold.co/300x300';
                                }
                            @endphp
                            <x-product-card 
                                :id="$relatedProduct->id" 
                                :slug="$relatedProduct->slug"
                                :image="$relatedProductImage"
                                :title="$relatedProduct->title" 
                                :price="$relatedProduct->price"
                                :discount="$relatedProduct->discount ?? 0"
                                :category="$relatedProduct->category->name ?? 'Uncategorized'"
                                :type="$relatedProduct->type ?? 'product'"
                                :on-sale="$relatedProduct->discount > 0"
																:type="$relatedProduct->type"
																 />
                        @endforeach
                    @else
                        <p class="text-center col-span-full text-base-content/70 mb-4">لا يوجد منتجات مرتبطة بهذا المنتج.</p>
                    @endif
                </div>
            </div>
</x-layouts.app>

<script>
    function productGallery(images) {
        return {
            images: Array.isArray(images) && images.length ? images : ['{{ $mainImage }}'],
            activeIndex: 0,
            zoomed: false,
            originX: 50,
            originY: 50,
            get activeImage() { return this.images[this.activeIndex] || this.images[0]; },
            setActive(i) { this.activeIndex = i; },
            onMove(e) {
                if (!this.zoomed) return;
                const rect = e.currentTarget.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                this.originX = Math.max(0, Math.min(100, x));
                this.originY = Math.max(0, Math.min(100, y));
            },
            get zoomStyle() {
                return this.zoomed
                    ? `transform: scale(2); transform-origin: ${this.originX}% ${this.originY}%;`
                    : 'transform: scale(1); transform-origin: center center;';
            }
        };
    }
</script>
