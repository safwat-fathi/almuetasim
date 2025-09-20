<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wireless Headphones - ModernShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.js"></script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .image-zoom {
            transition: transform 0.3s ease;
        }
        .image-zoom:hover {
            transform: scale(1.1);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .thumbnail {
            transition: all 0.3s ease;
        }
        .thumbnail:hover {
            transform: scale(1.05);
        }
        .thumbnail.active {
            border: 2px solid oklch(var(--p));
        }
    </style>
</head>
<body class="min-h-screen bg-base-100">
    <!-- Navigation -->
    <div class="navbar bg-base-100 shadow-lg sticky top-0 z-50">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path></svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <li><a>Electronics</a></li>
                    <li><a>Clothing</a></li>
                    <li><a>Home & Garden</a></li>
                    <li><a>Sports</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost text-xl font-bold" href="/">ModernShop</a>
        </div>
        
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a class="hover:text-primary">Electronics</a></li>
                <li><a class="hover:text-primary">Clothing</a></li>
                <li><a class="hover:text-primary">Home & Garden</a></li>
                <li><a class="hover:text-primary">Sports</a></li>
            </ul>
        </div>
        
        <div class="navbar-end gap-2">
            <div class="form-control hidden md:block">
                <input type="text" placeholder="Search products..." class="input input-bordered input-sm w-32 md:w-auto" />
            </div>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                        <i data-lucide="shopping-cart"></i>
                        <span class="badge badge-sm indicator-item badge-primary" id="cart-count">0</span>
                    </div>
                </div>
                <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-[1] mt-3 w-80 shadow-xl">
                    <div class="card-body">
                        <span class="text-lg font-bold">Cart Items</span>
                        <div id="cart-items" class="space-y-2">
                            <p class="text-base-content/70">Your cart is empty</p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary btn-block">View cart</button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-ghost btn-circle">
                <i data-lucide="user"></i>
            </button>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 py-4">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="/" class="text-primary hover:underline">Home</a></li>
                <li><a href="#" class="text-primary hover:underline">Electronics</a></li>
                <li><a href="#" class="text-primary hover:underline">Audio</a></li>
                <li>Wireless Headphones</li>
            </ul>
        </div>
    </div>

    <!-- Product Details -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4">
                <div class="relative overflow-hidden rounded-lg bg-base-200 aspect-square">
                    <img id="main-image" src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop&crop=center" 
                         alt="Wireless Headphones" class="w-full h-full object-cover image-zoom cursor-zoom-in"/>
                    <div class="badge badge-secondary absolute top-4 left-4">25% OFF</div>
                </div>
                
                <!-- Thumbnail Images -->
                <div class="grid grid-cols-4 gap-2">
                    <div class="thumbnail active aspect-square rounded-lg overflow-hidden cursor-pointer" 
                         onclick="changeImage('https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop&crop=center', this)">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=150&h=150&fit=crop&crop=center" 
                             alt="View 1" class="w-full h-full object-cover"/>
                    </div>
                    <div class="thumbnail aspect-square rounded-lg overflow-hidden cursor-pointer" 
                         onclick="changeImage('https://images.unsplash.com/photo-1484704849700-f032a568e944?w=600&h=600&fit=crop&crop=center', this)">
                        <img src="https://images.unsplash.com/photo-1484704849700-f032a568e944?w=150&h=150&fit=crop&crop=center" 
                             alt="View 2" class="w-full h-full object-cover"/>
                    </div>
                    <div class="thumbnail aspect-square rounded-lg overflow-hidden cursor-pointer" 
                         onclick="changeImage('https://images.unsplash.com/photo-1583394838336-acd977736f90?w=600&h=600&fit=crop&crop=center', this)">
                        <img src="https://images.unsplash.com/photo-1583394838336-acd977736f90?w=150&h=150&fit=crop&crop=center" 
                             alt="View 3" class="w-full h-full object-cover"/>
                    </div>
                    <div class="thumbnail aspect-square rounded-lg overflow-hidden cursor-pointer" 
                         onclick="changeImage('https://images.unsplash.com/photo-1545454675-3531b543be5d?w=600&h=600&fit=crop&crop=center', this)">
                        <img src="https://images.unsplash.com/photo-1545454675-3531b543be5d?w=150&h=150&fit=crop&crop=center" 
                             alt="View 4" class="w-full h-full object-cover"/>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold mb-2">Premium Wireless Headphones</h1>
                    <p class="text-base-content/70 mb-4">Premium noise-canceling wireless headphones with 30-hour battery life</p>
                    
                    <!-- Rating -->
                    <div class="flex items-center gap-2 mb-4">
                        <div class="rating rating-sm">
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" checked disabled />
                        </div>
                        <span class="text-sm text-base-content/70">4.8 (247 reviews)</span>
                        <span class="text-success text-sm font-medium">✓ In Stock</span>
                    </div>

                    <!-- Price -->
                    <div class="flex items-center gap-3 mb-6">
                        <span class="text-3xl font-bold text-primary">$99.99</span>
                        <span class="text-xl line-through text-base-content/50">$129.99</span>
                        <span class="badge badge-secondary">Save $30</span>
                    </div>
                </div>

                <!-- Color Options -->
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

                <!-- Quantity -->
                <div>
                    <h3 class="font-semibold mb-3">Quantity</h3>
                    <div class="flex items-center gap-3">
                        <div class="join">
                            <button class="btn join-item btn-sm" onclick="decreaseQty()">-</button>
                            <input type="number" id="quantity" value="1" min="1" class="input input-bordered join-item w-16 text-center input-sm" />
                            <button class="btn join-item btn-sm" onclick="increaseQty()">+</button>
                        </div>
                        <span class="text-sm text-base-content/70">Only 5 left in stock!</span>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="space-y-3">
                    <button class="btn btn-primary btn-lg w-full" onclick="addToCart()">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        Add to Cart
                    </button>
                    <div class="grid grid-cols-2 gap-3">
                        <button class="btn btn-outline btn-lg">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                            Wishlist
                        </button>
                        <button class="btn btn-outline btn-lg">
                            <i data-lucide="share-2" class="w-5 h-5"></i>
                            Share
                        </button>
                    </div>
                </div>

                <!-- Features -->
                <div class="border-t pt-6">
                    <h3 class="font-semibold mb-3">Key Features</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                            <span class="text-sm">Active Noise Cancellation</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                            <span class="text-sm">30-hour battery life</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                            <span class="text-sm">Bluetooth 5.0 connectivity</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                            <span class="text-sm">Quick charge: 5 min = 3 hours playback</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="mt-16">
            <div class="tabs tabs-bordered mb-8">
                <a class="tab tab-active" onclick="showTab('description')">Description</a>
                <a class="tab" onclick="showTab('specifications')">Specifications</a>
                <a class="tab" onclick="showTab('reviews')">Reviews (247)</a>
                <a class="tab" onclick="showTab('shipping')">Shipping</a>
            </div>

            <!-- Description Tab -->
            <div id="description" class="tab-content active">
                <div class="prose max-w-none">
                    <p class="text-lg mb-4">Experience premium sound quality with our flagship wireless headphones. Engineered with advanced noise-canceling technology and premium drivers, these headphones deliver exceptional audio performance for music lovers and professionals alike.</p>
                    
                    <h3>What's in the box:</h3>
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

            <!-- Specifications Tab -->
            <div id="specifications" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-lg mb-4">Technical Specifications</h3>
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
                                <span class="font-medium">Sarah Johnson</span>
                                <span class="text-sm text-base-content/70">2 days ago</span>
                            </div>
                            <p class="text-sm mb-2"><strong>Amazing sound quality!</strong></p>
                            <p class="text-sm text-base-content/70">These headphones exceeded my expectations. The noise cancellation is incredible and the battery life is exactly as advertised. Highly recommend!</p>
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
                            <p class="text-sm mb-2"><strong>Great for work from home</strong></p>
                            <p class="text-sm text-base-content/70">Perfect for video calls and music. Comfortable to wear all day. The quick charge feature is a lifesaver.</p>
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
                            <p class="text-sm text-base-content/70">The build quality is excellent and the sound is crystal clear. Love the carrying case too. Will definitely buy from ModernShop again.</p>
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
                                    <span class="font-bold">$9.99</span>
                                </div>
                                <p class="text-sm text-base-content/70">2-3 business days</p>
                            </div>
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">Next Day Delivery</span>
                                    <span class="font-bold">$19.99</span>
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
                            <div class="flex items-start gap-3">
                                <i data-lucide="headphones" class="w-5 h-5 text-success mt-1"></i>
                                <div>
                                    <h4 class="font-medium">2-Year Warranty</h4>
                                    <p class="text-sm text-base-content/70">Full manufacturer warranty included</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-8">Related Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-product-card 
                    id="9"
                    image="https://images.unsplash.com/photo-1484704849700-f032a568e944?w=400&h=400&fit=crop&crop=center"
                    title="Wireless Earbuds Pro"
                    price="79.99"
                    original-price="99.99"
                    on-sale="true" />
                
                <x-product-card 
                    id="10"
                    image="https://images.unsplash.com/photo-1583394838336-acd977736f90?w=400&h=400&fit=crop&crop=center"
                    title="Gaming Headset RGB"
                    price="59.99"
                    original-price="79.99"
                    on-sale="true" />
                
                <x-product-card 
                    id="11"
                    image="https://images.unsplash.com/photo-1545454675-3531b543be5d?w=400&h=400&fit=crop&crop=center"
                    title="Portable Bluetooth Speaker"
                    price="49.99"
                    original-price="69.99"
                    on-sale="true" />
                
                <x-product-card 
                    id="12"
                    image="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=400&fit=crop&crop=center"
                    title="Premium USB-C Cable"
                    price="19.99"
                    original-price="24.99"
                    on-sale="true" />
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer footer-center bg-base-300 text-base-content p-10 mt-16">
        <nav class="grid grid-flow-col gap-4">
            <a class="link link-hover">About us</a>
            <a class="link link-hover">Contact</a>
            <a class="link link-hover">Jobs</a>
            <a class="link link-hover">Press kit</a>
        </nav>
        <nav>
            <div class="grid grid-flow-col gap-4">
                <a class="btn btn-ghost btn-circle" href="#" aria-label="Twitter">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </a>
                <a class="btn btn-ghost btn-circle" href="#" aria-label="Facebook">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a class="btn btn-ghost btn-circle" href="#" aria-label="Instagram">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.948 16.126c-2.27 0-4.108-1.838-4.108-4.109 0-2.27 1.837-4.108 4.108-4.108 2.27 0 4.108 1.837 4.108 4.108 0 2.271-1.838 4.109-4.108 4.109zm7.982-8.681h-2.956v9.124h2.956V7.445z"/>
                    </svg>
                </a>
                <a class="btn btn-ghost btn-circle" href="#" aria-label="LinkedIn">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </a>
            </div>
        </nav>
        <aside>
            <p>Copyright © 2024 - All right reserved by ModernShop</p>
        </aside>
    </footer>

    <script>
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
    </script>
</body>
</html>