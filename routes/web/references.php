<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Droid\DroidTypeController;
use App\Http\Middleware\PasswordExpired;
use App\Http\Controllers\Reference\ReferenceController;


Route::middleware(['auth'], PasswordExpired::class)->prefix('references')->group(function () {
    Route::get('/accounts', [ReferenceController::class, 'accounts']);
    Route::get('/agreements', [ReferenceController::class, 'agreements']);
    Route::get('/projects', [ReferenceController::class, 'projects']);
    Route::get('/cfs-items', [ReferenceController::class, 'cfsItems']);
    Route::get('/beneficiaries', [ReferenceController::class, 'beneficiaries']);
    Route::get('/pl-items', [ReferenceController::class, 'plItems']);
});