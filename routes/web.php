<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    // Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('/products', 'pages::reference.products.index')
        ->name('product')->can('viewAny', Product::class);

    Route::group(['prefix' => 'reference', 'as' => 'reference.'], function () {
        Route::view('/brands', 'pages::reference.brands.index')
            ->name('brand')->can('viewAny', Brand::class);
        Route::view('/categories', 'pages::reference.categories.index')
            ->name('category')->can('viewAny', Category::class);
    });
});

require __DIR__.'/settings.php';
