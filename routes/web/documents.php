<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;
use App\Http\Controllers\DocumentController;

Route::group([
    'prefix' => 'documents', 'middleware' => ['auth:web',PasswordExpired::class,'permission:e-counterparty']
],
    function () {
        Route::get('{document}/preview', [DocumentController::class, 'preview'])
            ->name('documentPreview');
        Route::post('documents/upload', [DocumentController::class, 'uploadFile'])
            ->name('documentUpload');
    }
);

