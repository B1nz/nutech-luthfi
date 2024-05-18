<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/login', 'showLoginForm')->name('login-form');
    Route::get('/register', 'showRegisterForm')->name('register-form');
});

// Opening other page require login
Route::middleware(['auth'])->group(function () {
    // Home Route
    // Route::get('/', [HomeController::class, 'index']);

    //Index Route
    Route::get('/', [HomeController::class, 'home'])->name('products');

    // Product Route
    Route::get('/products/add', [ProductController::class, 'add'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/delete/{id}', [ProductController::class, 'delete'])->name('products.delete');

    // Profile Route
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'profileUpdate'])->name('profileUpdate');

    // Export Product List to Excel
    Route::post('/export-products', [ExportController::class, 'export'])->name('export.products');
});

