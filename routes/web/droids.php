<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Droid\DroidTypeController;
use App\Http\Middleware\PasswordExpired;


Route::middleware(['auth:web',PasswordExpired::class,'permission:s-droid_types'])->group(function () {
    Route::get('droidTypes', [DroidTypeController::class, 'index'])
    ->name('droidTypes.index');
    Route::get('droidTypes/{droidType}/summary/{page?}', [DroidTypeController::class, 'summary'])->name('droidTypes.summary');    
});

Route::middleware(['auth:web',PasswordExpired::class,'permission:e-droid_types'])->group(function () {
    Route::get('droidTypes/create', [DroidTypeController::class, 'create'])->name('droidTypes.create');
    Route::post('droidTypes/create', [DroidTypeController::class, 'store'])->name('droidTypes.store');
    Route::get('droidTypes/{id}/edit', [DroidTypeController::class, 'edit'])->name('droidTypes.edit');
    Route::put('droidTypes/{id}', [DroidTypeController::class, 'update'])->name('droidTypes.update');
    Route::delete('droidTypes/{id}', [DroidTypeController::class, 'destroy'])->name('droidTypes.destroy');
});
