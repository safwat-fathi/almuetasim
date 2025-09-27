<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);

Route::get('/category/{categorySlug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/product/{productSlug}', [ProductController::class, 'show'])->name('product.show');

// Wishlist routes
Route::post('/wishlist/add/{productId}', function ($productId) {
	$wishlist = session()->get('wishlist', []);
	if (!in_array($productId, $wishlist)) {
		$wishlist[] = $productId;
		session()->put('wishlist', $wishlist);
	}
	return response()->json(['success' => true, 'count' => count($wishlist)]);
})->name('wishlist.add');

Route::post('/wishlist/remove/{productId}', function ($productId) {
	$wishlist = session()->get('wishlist', []);
	$wishlist = array_diff($wishlist, [$productId]);
	session()->put('wishlist', $wishlist);
	return response()->json(['success' => true, 'count' => count($wishlist)]);
})->name('wishlist.remove');

Route::get('/wishlist/count', function () {
	$count = count(session()->get('wishlist', []));
	return response()->json(['count' => $count]);
})->name('wishlist.count');

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
});

// Admin messages routes
Route::middleware(['auth'])->group(function () {
	Route::get('/admin/messages', [MessagesController::class, 'index'])->name('admin.messages.index');
	Route::get('/admin/messages/{message}', [MessagesController::class, 'show'])->name('admin.messages.show');
	Route::patch('/admin/messages/{message}/read', [MessagesController::class, 'markAsRead'])->name('admin.messages.markAsRead');
});
