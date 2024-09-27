<?php

use App\Http\Controllers\BigSearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Middleware\PasswordExpired;


Route::middleware(['auth:web',PasswordExpired::class])->group(function () {
    Route::get('search', [BigSearchController::class, 'index'])
    ->name('bigSearch');
});
