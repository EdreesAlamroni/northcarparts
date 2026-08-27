<?php

use Illuminate\Support\Facades\Route;

Route::name('website.')->group(function () {
    Route::view('/', 'welcome')->name('welcome');
    Route::view('/', 'welcome')->name('home');
});
