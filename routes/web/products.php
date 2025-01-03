<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;
use App\Http\Middleware\PasswordExpired;
use App\Http\Middleware\CheckSuperuser;


Route::middleware(['auth:web',PasswordExpired::class])->group(function () {
    Route::get('products-and-services', [ProductController::class, 'index'])
    ->name('products.index');
});

Route::middleware(['auth:web',PasswordExpired::class, CheckSuperuser::class])
    ->prefix('products')->group(function () {
        Route::get('products-and-services/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products-and-services/create', [ProductController::class, 'store'])->name('products.store');
        Route::get('products-and-services/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products-and-services/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products-and-services/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});
