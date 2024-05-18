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
    //Index Route
    Route::get('/', [HomeController::class, 'home'])->name('home');

    // Product Route
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products/add', 'add')->name('products.create');
        Route::post('/products', 'store')->name('products.store');
        Route::get('/products/edit/{id}', 'edit')->name('product.edit');
        Route::post('/products/update/{id}', 'update')->name('product.update');
        Route::delete('/products/delete/{id}', 'delete')->name('products.delete');
    });

    // Profile Route
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/profile', 'profileUpdate')->name('profile.update');
    });


    // Export Product List to Excel
    Route::post('/export-products', [ExportController::class, 'export'])->name('export.products');
});

