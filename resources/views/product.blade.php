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
                <li><a href="{{ route('category.show', $product->category->slug) }}"
                        class="text-primary hover:underline">{{ $product->category->name }}</a></li>
                <li>{{ $product->title }}</li>
            </ul>
        </div>
    </div>

    <!-- Product Details -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4">
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
                <div class="relative overflow-hidden rounded-lg bg-base-200 aspect-square">
                    <img id="main-image" src="{{ $mainImage }}" alt="{{ $product->title }}"
                        class="w-full h-full object-cover image-zoom cursor-zoom-in" />
                    @if ($product->price < 1000)
                        {{-- Simple discount logic for demo --}}
                        <div class="badge badge-secondary absolute top-4 left-4">
                            {{ round((1000 - $product->price) / 10) }}% OFF
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Images -->
                @if (count($processedImages) > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach ($processedImages as $index => $image)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }} aspect-square rounded-lg overflow-hidden cursor-pointer"
                                onclick="changeImage('{{ $image }}', this)">
                                <img src="{{ $image }}" alt="View {{ $index + 1 }}"
                                    class="w-full h-full object-cover" />
                            </div>
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
                    <div class="flex items-center gap-3 mb-6">
                        <span class="text-3xl font-bold text-primary">{{ number_format($product->price, 2) }}
                            EGP</span>
                        @if ($product->price < 1000)
                            {{-- Simple discount logic for demo --}}
                            <span class="text-xl line-through text-base-content/50">{{ number_format(1000, 2) }}
                                EGP</span>
                            <span class="badge badge-secondary">Save {{ number_format(1000 - $product->price, 2) }}
                                EGP</span>
                        @endif
                    </div>
                </div>

                <!-- Color Options -->
                @if (!$product->is_part)
                    <div>
                        <h3 class="font-semibold mb-3">Color</h3>
                        <div class="flex gap-2">
                            <div class="form-control">
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" class="radio radio-primary" checked />
                                    <span class="ml-2">Black</span>
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" class="radio radio-primary" />
                                    <span class="ml-2">White</span>
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" class="radio radio-primary" />
                                    <span class="ml-2">Blue</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

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
                    showToast(message, type = 'info') {
                        const toast = document.createElement('div');
                        toast.className = 'toast toast-top toast-end z-50';
                        toast.innerHTML = `
                            <div class="alert alert-${type}">
                                <span>${message}</span>
                            </div>
                        `;
                        document.body.appendChild(toast);
                        
                        setTimeout(() => {
                            toast.remove();
                        }, 3000);
											}
                }">
                    {{-- <button class="btn btn-primary btn-lg w-full"
                        onclick="addToCart({{ $product->id }}, '{{ $product->title }}', {{ $product->price }}, '{{ $mainImage }}')">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        Add to Cart
                    </button> --}}
                    <div class="grid grid-cols-2 gap-3">
                        <button class="btn btn-outline btn-lg" 
                            :class="{ 'loading': wishlistLoading }"
                            x-on:click="wishlistLoading = true; fetch('{{ route('wishlist.add', $product->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(response => response.json()).then(data => { 
                                wishlistLoading = false; 
                                document.getElementById('wishlist-count').textContent = data.count;
                                showToast('Added to wishlist!', 'success');
                            }).catch(error => { 
                                wishlistLoading = false; 
                                showToast('Error adding to wishlist', 'error');
                            });">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                            Wishlist
                        </button>
                        <button class="btn btn-outline btn-lg"
                            :class="{ 'loading': shareLoading }"
                            x-on:click="shareLoading = true; navigator.clipboard.writeText(window.location.href).then(() => { 
                                shareLoading = false; 
                                showToast('URL copied to clipboard!', 'success');
                            }).catch(err => { 
                                shareLoading = false; 
                                showToast('Failed to copy URL', 'error');
                            });">
                            <i data-lucide="share-2" class="w-5 h-5"></i>
                            Share
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
                    <h3 class="font-semibold mb-3">Key Features</h3>
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
            <div class="tabs tabs-bordered mb-8">
                <input type="radio" name="product_tabs" class="tab mb-4" aria-label="Description" checked />
                <div class="tab-content">
                    <div class="prose max-w-none">
                        <p class="text-lg mb-4">
                            Experience premium sound quality with our flagship wireless
                            headphones. Engineered with advanced noise-canceling technology
                            and premium drivers, these headphones deliver exceptional audio
                            performance for music lovers and professionals alike.
                        </p>

                        <h3>What&apos;s in the box:</h3>
                        <ul>
                            <li>Premium Wireless Headphones</li>
                            <li>USB-C Charging Cable</li>
                            <li>3.5mm Audio Cable</li>
                            <li>Carrying Case</li>
                            <li>User Manual & Warranty Card</li>
                        </ul>

                        <h3>Perfect for:</h3>
                        <ul>
                            <li>Music enthusiasts seeking premium sound quality</li>
                            <li>Professionals working in noisy environments</li>
                            <li>Travelers wanting comfort and noise isolation</li>
                            <li>Gamers and content creators</li>
                        </ul>
                    </div>
                </div>

                <input type="radio" name="product_tabs" class="tab mb-4" aria-label="Specifications" />
                <div class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-bold text-lg mb-4">
                                Technical Specifications
                            </h3>
                            <div class="space-y-2">
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Driver Size:</span>
                                    <span>40mm Dynamic</span>
                                </div>
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Frequency Response:</span>
                                    <span>20Hz - 20kHz</span>
                                </div>
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Impedance:</span>
                                    <span>32Ω</span>
                                </div>
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Sensitivity:</span>
                                    <span>105dB</span>
                                </div>
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Weight:</span>
                                    <span>250g</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-4">Connectivity & Battery</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Bluetooth Version:</span>
                                    <span>5.0</span>
                                </div>
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Range:</span>
                                    <span>10m / 33ft</span>
                                </div>
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Battery Life:</span>
                                    <span>30 hours</span>
                                </div>
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Charging Time:</span>
                                    <span>2 hours</span>
                                </div>
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">Quick Charge:</span>
                                    <span>5 min = 3 hours</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="prose max-w-none">
                    <p class="text-lg mb-4">{{ $product->description }}</p>

                    <h3>Product Details:</h3>
                    <ul>
                        <li>SKU: {{ $product->sku }}</li>
                        <li>Category: {{ $product->category->name }}</li>
                        @if ($product->is_part)
                        <li>This is a replacement part/accessory</li>
                        @else
                        <li>This is a complete product</li>
                        @endif
                    </ul>

                    <h3>Perfect for:</h3>
                    <ul>
                        <li>Customers looking for quality {{ $product->category->name }}</li>
                        <li>Those who value reliability and performance</li>
                        <li>Anyone seeking great value for money</li>
                    </ul>
                </div>
            </div>
            </div>

            <!-- Specifications Tab -->
            <div id="specifications" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-lg mb-4">Product Specifications</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between border-b pb-1">
                                <span class="font-medium">SKU:</span>
                                <span>{{ $product->sku }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-1">
                                <span class="font-medium">Category:</span>
                                <span>{{ $product->category->name }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-1">
                                <span class="font-medium">Type:</span>
                                <span>{{ $product->is_part ? 'Replacement Part' : 'Complete Product' }}</span>
                            </div>
                            @if ($product->warranty_months > 0)
                            <div class="flex justify-between border-b pb-1">
                                <span class="font-medium">Warranty:</span>
                                <span>{{ $product->warranty_months }} months</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-4">Technical Details</h3>
                        <div class="space-y-2">
                            @if (isset($product->specs) && is_array($product->specs))
                                @foreach ($product->specs as $key => $value)
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium">{{ ucfirst($key) }}:</span>
                                    <span>{{ $value }}</span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-base-content/70">No additional specifications available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div id="reviews" class="tab-content">
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
                            <div class="text-sm text-base-content/70">247 reviews</div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">5★</span>
                                <progress class="progress progress-primary w-24" value="85"
                                    max="100"></progress>
                                <span class="text-sm">85%</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">4★</span>
                                <progress class="progress progress-primary w-24" value="12"
                                    max="100"></progress>
                                <span class="text-sm">12%</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">3★</span>
                                <progress class="progress progress-primary w-24" value="2"
                                    max="100"></progress>
                                <span class="text-sm">2%</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">2★</span>
                                <progress class="progress progress-primary w-24" value="1"
                                    max="100"></progress>
                                <span class="text-sm">1%</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm w-8">1★</span>
                                <progress class="progress progress-primary w-24" value="0"
                                    max="100"></progress>
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
                                <span class="font-medium">Sarah Johnson</span>
                                <span class="text-sm text-base-content/70">2 days ago</span>
                            </div>
                            <p class="text-sm mb-2"><strong>Amazing quality!</strong></p>
                            <p class="text-sm text-base-content/70">This {{ $product->title }} exceeded my expectations. The build quality is excellent and it works perfectly. Highly recommend!</p>
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
                                <span class="font-medium">Mike Chen</span>
                                <span class="text-sm text-base-content/70">1 week ago</span>
                            </div>
                            <p class="text-sm mb-2"><strong>Great value for money</strong></p>
                            <p class="text-sm text-base-content/70">Perfect {{ $product->category->name }}. Works exactly as described. The price is very reasonable for the quality you get.</p>
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
                                <span class="font-medium">Emily Rodriguez</span>
                                <span class="text-sm text-base-content/70">2 weeks ago</span>
                            </div>
                            <p class="text-sm mb-2"><strong>Best purchase this year!</strong></p>
                            <p class="text-sm text-base-content/70">The quality is excellent and it arrived quickly. Will definitely buy from this store again.</p>
                        </div>

                        <button class="btn btn-outline w-full">Load More Reviews</button>
                    </div>
                </div>
            </div>

            <!-- Shipping Tab -->
            <div id="shipping" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-lg mb-4">Shipping Options</h3>
                        <div class="space-y-4">
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">Standard Delivery</span>
                                    <span class="text-success font-bold">FREE</span>
                                </div>
                                <p class="text-sm text-base-content/70">5-7 business days</p>
                            </div>
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">Express Delivery</span>
                                    <span class="font-bold">99.99 EGP</span>
                                </div>
                                <p class="text-sm text-base-content/70">2-3 business days</p>
                            </div>
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">Next Day Delivery</span>
                                    <span class="font-bold">199.99 EGP</span>
                                </div>
                                <p class="text-sm text-base-content/70">Order by 2 PM for next day delivery</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-4">Return Policy</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <i data-lucide="shield-check" class="w-5 h-5 text-success mt-1"></i>
                                <div>
                                    <h4 class="font-medium">30-Day Returns</h4>
                                    <p class="text-sm text-base-content/70">Return within 30 days for a full refund</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i data-lucide="truck" class="w-5 h-5 text-success mt-1"></i>
                                <div>
                                    <h4 class="font-medium">Free Return Shipping</h4>
                                    <p class="text-sm text-base-content/70">We'll cover the return shipping costs</p>
                                </div>
                            </div>
                            @if ($product->warranty_months > 0)
                            <div class="flex items-start gap-3">
                                <i data-lucide="headphones" class="w-5 h-5 text-success mt-1"></i>
                                <div>
                                    <h4 class="font-medium">{{ $product->warranty_months }}-Month Warranty</h4>
                                    <p class="text-sm text-base-content/70">Full manufacturer warranty included</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}
            </div>

            <!-- Related Products -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold mb-8">Related Products</h2>
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
                                :category="$relatedProduct->category->name ?? 'Uncategorized'"
                                :type="$relatedProduct->type ?? 'product'"
                                :on-sale="$relatedProduct->price < 1000"
																:type="$relatedProduct->type"
																 />
                        @endforeach
                    @else
                        <p class="text-center col-span-full text-base-content/70 mb-4">لا يوجد منتجات مرتبطة بهذا المنتج.</p>
                    @endif
                </div>
            </div>
</x-layouts.app>

{{-- <script>
        // Shopping cart
        let cart = [];

        // Initialize Lucide icons and setup
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            updateCartDisplay();
        });

        // Image gallery functionality
        function changeImage(imageUrl, thumbnail) {
            document.getElementById('main-image').src = imageUrl;
            
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Add active class to clicked thumbnail
            thumbnail.classList.add('active');
        }

        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('tab-active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('tab-active');
        }

        // Quantity controls
        function increaseQty() {
            const qtyInput = document.getElementById('quantity');
            qtyInput.value = parseInt(qtyInput.value) + 1;
        }

        function decreaseQty() {
            const qtyInput = document.getElementById('quantity');
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        }

        // Add to cart functionality (main product)
        function addToCart() {
            const quantity = parseInt(document.getElementById('quantity').value);
            const product = {
                id: 1,
                name: "Premium Wireless Headphones",
                price: 99.99,
                image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop&crop=center",
                quantity: quantity
            };

            const existingItem = cart.find(item => item.id === product.id);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push(product);
            }
            
            updateCartDisplay();
            
            // Show success toast
            showToast(`Added ${quantity} item(s) to cart!`, 'success');
        }

        // Add to cart functionality (generic)
        function addToCartGeneric(productId, productName, price, image) {
            const product = {
                id: productId,
                name: productName,
                price: price,
                image: image,
                quantity: 1
            };

            const existingItem = cart.find(item => item.id === product.id);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push(product);
            }
            
            updateCartDisplay();
            
            // Show success toast
            showToast(`Added ${productName} to cart!`, 'success');
        }

        // Update cart display
        function updateCartDisplay() {
            const cartCount = document.getElementById('cart-count');
            const cartItems = document.getElementById('cart-items');
            
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-base-content/70">Your cart is empty</p>';
            } else {
                cartItems.innerHTML = cart.map(item => `
                    <div class="flex items-center gap-2 py-2">
                        <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded">
                        <div class="flex-1">
                            <p class="font-semibold text-sm">${item.name}</p>
                            <p class="text-xs text-base-content/70">${item.price} x ${item.quantity}</p>
                        </div>
                        <button class="btn btn-ghost btn-xs" onclick="removeFromCart(${item.id})">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    </div>
                `).join('');
                lucide.createIcons();
            }
        }

        // Remove from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartDisplay();
        }

        // Toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.class = 'toast toast-top toast-end z-50';
            toast.innerHTML = `
                <div class="alert alert-${type}">
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script> --}}
