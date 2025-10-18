<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UpdateProfileController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users/{id}/image', [UpdateProfileController::class, 'getImage'])->name('users.get-image');
    Route::put('/users/{id}/update-image', [UpdateProfileController::class, 'updateImage'])->name('users.update-image');
    Route::delete('/users/{id}/remove-image', [UpdateProfileController::class, 'removeImage'])->name('users.remove-image');
});

Route::middleware('auth')->group(function () {
    Route::get('/cart', function () {
        return Inertia::render('Cart/Index');
    })->name('cart.index');
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

    Route::controller(TransactionController::class)
        ->as('transactions.')
        ->group(function () {
            Route::get('/transactions', 'index')->name('index');
            Route::post('/transactions/create', 'store')->name('store');
            Route::get('/checkout/success/{transaction}', 'checkoutSuccess')->name('checkout.success');
            Route::get('/transactions/history', 'transactionHistory')->name('history');
            Route::get('/transactions/{transaction_code}', 'transactionHistoryDetail')
                ->name('show');
        });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
