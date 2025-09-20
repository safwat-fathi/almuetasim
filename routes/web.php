<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);

Route::get('/category/{categoryName}', [CategoryController::class, 'show']);

Route::get('/product', function () {
		return view('product');
});

Route::get('/dashboard', function () {
		return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');