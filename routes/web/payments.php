<?php

use App\Http\Controllers\Payment\AdeskRuleController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;
use App\Http\Controllers\Payment\BankAccountController;
use App\Http\Controllers\Payment\ImportADeskOperationController;
use App\Http\Controllers\Payment\PaymentController;


Route::middleware(['auth:web',PasswordExpired::class,'permission:s-accounts'])->group(function () {
    Route::get('accounts', [BankAccountController::class, 'index'])
    ->name('accounts.index');
    Route::get('accounts/{bankAccount}/summary/{page?}', [BankAccountController::class, 'summary'])->name('accounts.summary');    
});

Route::middleware(['auth:web',PasswordExpired::class,'permission:e-accounts'])->group(function () {
    Route::get('accounts/create/{company?}', [BankAccountController::class, 'create'])->name('accounts.create');
    Route::post('accounts', [BankAccountController::class, 'store'])->name('accounts.store');
    Route::get('accounts/{bankAccount}/edit', [BankAccountController::class, 'edit'])->name('accounts.edit');
    Route::put('accounts/{bankAccount}', [BankAccountController::class, 'update'])->name('accounts.update');
    Route::delete('accounts/{bankAccount}', [BankAccountController::class, 'destroy'])->name('accounts.destroy');
});

Route::middleware(['auth:web',PasswordExpired::class,'permission:s-payments'])->group(function () {
    Route::get('payments', [PaymentController::class, 'index'])
    ->name('payments.index');
});

Route::middleware(['auth:web',PasswordExpired::class,'permission:e-payments'])->group(function () {
    Route::get('payments/create/{agreement?}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});

Route::middleware(['auth:web',PasswordExpired::class,'permission:e-payments'])->group(function () {
    Route::get('payments/import/adesk', [ImportADeskOperationController::class, 'index'])->name('import.adesk.payments.index');
    Route::get('payments/import/adesk/edit/{adesk_id}', [ImportADeskOperationController::class, 'edit'])->name('import.adesk.payments.edit');
    Route::put('payments/import/adesk/edit/{adesk_id}', [ImportADeskOperationController::class, 'update'])->name('import.adesk.payments.update');
    Route::get('payments/import/adesk/apply_rules', [ImportADeskOperationController::class, 'applyRules'])->name('import.adesk.payments.applyRules');
    Route::get('payments/import/adesk/proceed_adesk', [ImportADeskOperationController::class, 'processAdeskOperations'])->name('import.adesk.payments.proceedAdeskOps');
});


Route::middleware(['auth:web',PasswordExpired::class,'permission:e-payments'])->group(function () {
    Route::get('payments/import/adesk/rules', [AdeskRuleController::class, 'index'])->name('import.adesk.rules.index');
    Route::get('payments/import/adesk/rules/create/{adesk_id?}', [AdeskRuleController::class, 'create'])->name('import.adesk.rules.create');
    Route::post('payments/import/adesk/rules/create/{adesk_id?}', [AdeskRuleController::class, 'store']);
    Route::get('payments/import/adesk/rules/edit/{adeskRule}', [AdeskRuleController::class, 'edit'])->name('import.adesk.rules.edit');
    Route::post('payments/import/adesk/rules/edit/{adeskRule}', [AdeskRuleController::class, 'update']);
    Route::delete('payments/import/adesk/delete/{adeskRule}', [AdeskRuleController::class, 'destroy'])->name('import.adesk.rules.destroy');
});
