<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;
use App\Http\Controllers\Accrual\AccrualController;


Route::middleware(['auth:web',PasswordExpired::class,'permission:s-accruals'])->group(function () {
    Route::get('accruals', [AccrualController::class, 'index'])
    ->name('accruals.index');
});


Route::middleware(['auth:web',PasswordExpired::class,'permission:e-accruals'])->group(function () {
    Route::get('accruals/create/{agreement?}', [AccrualController::class, 'create'])->name('accruals.create');
    Route::post('accruals', [AccrualController::class, 'store'])->name('accruals.store');
    Route::get('accruals/{accrual}/edit', [AccrualController::class, 'edit'])->name('accruals.edit');
    Route::put('accruals/{accrual}', [AccrualController::class, 'update'])->name('accruals.update');
    Route::delete('accruals/{accrual}', [AccrualController::class, 'destroy'])->name('accruals.destroy');
});
