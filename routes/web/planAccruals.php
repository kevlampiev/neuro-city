<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;
use App\Http\Controllers\Accrual\PlanAccrualController;

Route::middleware(['auth:web',PasswordExpired::class,'permission:e-accruals'])->group(function () {
    Route::get('plan-accruals/create/{agreement?}', [PlanAccrualController::class, 'create'])->name('plan-accruals.add');
    Route::post('plan-accruals/create/{agreement?}', [PlanAccrualController::class, 'store']);

    Route::get('plan-accruals/{accrual}/edit', [PlanAccrualController::class, 'edit'])->name('plan-accruals.edit');
    Route::post('plan-accruals/{accrual}/edit', [PlanAccrualController::class, 'update']);
    Route::match(['post', 'get'],'plan-accruals/{accrual}', [PlanAccrualController::class, 'destroy'])->name('plan-accruals.destroy');
});

