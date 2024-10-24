<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;
use App\Http\Controllers\Payment\BankAccountController;


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

