<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::controller(CategoryController::class)
        ->as('categories.')
        ->group(function () {
            Route::get('/categories', 'index')->name('index');
            Route::post('/categories/create', 'store')->name('store');
            Route::put('/categories/update/{category}', 'update')->name('update');
            Route::delete('/categories/delete/{category}', 'destroy')->name('destroy');
        });
    Route::controller(ProductController::class)
        ->as('products.')
        ->group(function () {
            Route::get('/products', 'index')->name('index');
            Route::get('/products/create', 'create')->name('create');
            Route::post('/products/create', 'store')->name('store');
            Route::put('/products/update/{product}', 'update')->name('update');
            Route::delete('/products/delete/{product}', 'destroy')->name('destroy');
        });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
