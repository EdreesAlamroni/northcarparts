<?php

use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\ManufacturerController;
use App\Http\Controllers\Dashboard\NewsController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\SpecificationController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Manufacturers
    Route::prefix('manufacturers')->group(function () {
        Route::get('/', [ManufacturerController::class, 'index'])->name('manufacturers.index');
        Route::get('/create', [ManufacturerController::class, 'create'])->name('manufacturers.create');
        Route::post('/', [ManufacturerController::class, 'store'])->name('manufacturers.store');
        Route::get('/{manufacturer}', [ManufacturerController::class, 'show'])->name('manufacturers.show');
        Route::get('/{manufacturer}/edit', [ManufacturerController::class, 'edit'])->name('manufacturers.edit');
        Route::put('/{manufacturer}', [ManufacturerController::class, 'update'])->name('manufacturers.update');
        Route::delete('/{manufacturer}', [ManufacturerController::class, 'destroy'])->name('manufacturers.destroy');
    });

    // Brands
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/{brand}', [BrandController::class, 'show'])->name('brands.show');
        Route::get('/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    });

    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Specifications
    Route::prefix('specifications')->group(function () {
        Route::get('/', [SpecificationController::class, 'index'])->name('specifications.index');
    });

    // News
    Route::prefix('news')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('news.index');
        Route::get('/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('/', [NewsController::class, 'store'])->name('news.store');
        Route::get('/{news}', [NewsController::class, 'show'])->name('news.show');
        Route::get('/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('/{news}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::livewire('/', 'pages::dashboard.settings.general-settings')->name('settings.edit');
        Route::livewire('/social', 'pages::dashboard.settings.social-settings')->name('settings.social.edit');
    });

    // Account Settings
    Route::prefix('account-settings')->group(function () {
        Route::redirect('/', '/dashboard/account-settings/profile');

        Route::livewire('/profile', 'pages::dashboard.account-settings.profile')->name('account-settings.profile.edit');
        Route::livewire('/security', 'pages::dashboard.account-settings.security')->name('account-settings.security.edit');
    });
});
