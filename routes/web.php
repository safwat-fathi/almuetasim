<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/category/{categorySlug}', [CategoryController::class, 'showPublic'])->name('category.show');

Route::get('/product/{productSlug}', [ProductController::class, 'show'])->name('product.show');

// Public product listing page
Route::get('/products', [ProductController::class, 'indexPublic'])->name('products.public.list');

// Public products endpoint (for AJAX search/filter on homepage)
Route::get('/api/products', [ProductController::class, 'publicIndex'])->name('products.public.index');

// Wishlist routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add/{productId}', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/remove/{productId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
// Check if a given product is in the wishlist (AJAX)
Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check'])->name('wishlist.check');
// Return dropdown HTML and count for navbar (AJAX)
Route::get('/wishlist/dropdown', [WishlistController::class, 'dropdown'])->name('wishlist.dropdown');
Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');

// Cart routes
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart/items', [CartController::class, 'items'])->name('cart.items');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:5,10');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Contact form route
Route::post('/contact', [MessagesController::class, 'store'])->name('contact.store');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/admin/dashboard/search-products', [DashboardController::class, 'searchProducts'])->middleware(['auth'])->name('dashboard.search-products');

// Admin products management routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/products/search', [ProductController::class, 'search'])->name('admin.products.search');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});

// Admin categories routes
Route::middleware(['auth'])->group(function () {
    Route::resource('/admin/categories', CategoryController::class)
        ->except(['show'])
        ->names([
            'index' => 'admin.categories.index',
            'create' => 'admin.categories.create',
            'store' => 'admin.categories.store',
            'edit' => 'admin.categories.edit',
            'update' => 'admin.categories.update',
            'destroy' => 'admin.categories.destroy',
        ]);

    // API route for showing a category by ID (for admin modal)
    Route::get('/admin/categories/{id}', [CategoryController::class, 'show'])->name('admin.categories.show');
});

// Admin messages routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/messages', [MessagesController::class, 'index'])->name('admin.messages.index');
    Route::get('/admin/messages/{message}', [MessagesController::class, 'show'])->name('admin.messages.show');
    Route::patch('/admin/messages/{message}/read', [MessagesController::class, 'markAsRead'])->name('admin.messages.markAsRead');
});

// Admin settings routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('admin.settings.store');
});

// Admin Orders Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
	Route::get('/orders', [\App\Http\Controllers\AdminOrderController::class, 'index'])->name('orders.index');
	Route::get('/orders/{id}', [\App\Http\Controllers\AdminOrderController::class, 'show'])->name('orders.show');
	Route::patch('/orders/{id}/status', [\App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});
