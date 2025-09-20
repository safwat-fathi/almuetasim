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

// Contact form route
Route::post('/contact', [MessagesController::class, 'store'])->name('contact.store');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/admin/dashboard/search-products', [DashboardController::class, 'searchProducts'])->middleware(['auth'])->name('dashboard.search-products');

// Admin messages routes
Route::middleware(['auth'])->group(function () {
	Route::get('/admin/messages', [MessagesController::class, 'index'])->name('admin.messages.index');
	Route::get('/admin/messages/{message}', [MessagesController::class, 'show'])->name('admin.messages.show');
	Route::patch('/admin/messages/{message}/read', [MessagesController::class, 'markAsRead'])->name('admin.messages.markAsRead');
});