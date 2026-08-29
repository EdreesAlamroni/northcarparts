<?php

use App\Http\Controllers\Website\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::name('website.')->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
    Route::get('/', [WelcomeController::class, 'index'])->name('home');
});
