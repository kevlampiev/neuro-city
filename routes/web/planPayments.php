<?php

use App\Http\Controllers\Payment\MassPlanPaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;
use App\Http\Controllers\Payment\PlanPaymentController;

Route::middleware(['auth:web',PasswordExpired::class,'permission:e-payments'])->group(function () {
    Route::get('plan-payments/create/{agreement?}', [PlanPaymentController::class, 'create'])->name('plan-payments.add');
    Route::post('plan-payments/create/{agreement?}', [PlanPaymentController::class, 'store']);

    Route::get('plan-payments/mass-create/{agreement?}', [MassPlanPaymentController::class, 'create'])->name('plan-payments.mass-add');
    Route::post('plan-payments/mass-create/{agreement?}', [MassPlanPaymentController::class, 'store']);

    Route::get('plan-payments/{payment}/edit', [PlanPaymentController::class, 'edit'])->name('plan-payments.edit');
    Route::post('plan-payments/{payment}/edit', [PlanPaymentController::class, 'update']);
    Route::match(['post', 'get'],'plan-payments/{payment}', [PlanPaymentController::class, 'destroy'])->name('plan-payments.destroy');
});

