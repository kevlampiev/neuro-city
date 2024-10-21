<?php

use App\Http\Controllers\Budget\PlGroupController;
use App\Http\Controllers\Budget\PlItemController;
use App\Http\Middleware\PasswordExpired;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'pl-groups', 'middleware' =>['auth:web',PasswordExpired::class]
],
    function () {
        Route::get('/', [PlGroupController::class, 'index'])
            ->name('plGroups');
    }
);


Route::group([
    'prefix' => 'pl-groups', 'middleware' =>['auth:web',PasswordExpired::class, 'permission:e-ref_books']
],
    function () {
        Route::get('add', [PlGroupController::class, 'create'])
            ->name('addPlGroup');
        Route::post('add', [PlGroupController::class, 'store']);
        Route::get('{plGroup}/edit', [PlGroupController::class, 'edit'])
            ->name('editPlGroup');
        Route::post('{plGroup}/edit', [PlGroupController::class, 'update']);
        Route::match(['post', 'get'],
            '{plGroup}/delete', [PlGroupController::class, 'destroy'])
            ->name('deletePlGroup');
    }
);

Route::group([
    'prefix' => 'pl-items', 'middleware' =>['auth:web',PasswordExpired::class, 'permission:e-ref_books']
],
    function () {
        Route::get('add/{plGroup}', [PlItemController::class, 'create'])
            ->name('addPlItem');
        Route::post('add/{plGroup}', [PlItemController::class, 'store']);
        Route::get('{plItem}/edit', [PlItemController::class, 'edit'])
            ->name('editPlItem');
        Route::post('{plItem}/edit', [PlItemController::class, 'update']);
        Route::match(['post', 'get'],
            '{plItem}/delete', [PlItemController::class, 'destroy'])
            ->name('deletePlItem');
    }
);