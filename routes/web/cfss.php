<?php

use App\Http\Controllers\Budget\CFSGroupController;
use App\Http\Controllers\Budget\CFSItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;

Route::group([
    'prefix' => 'cfs-groups', 'middleware' =>['auth:web',PasswordExpired::class]
],
    function () {
        Route::get('/', [CFSGroupController::class, 'index'])
            ->name('cfsGroups');
    }
);

Route::group([
    'prefix' => 'cfs-groups', 'middleware' =>['auth:web',PasswordExpired::class, 'permission:e-ref_books']
],
    function () {
        Route::get('add', [CFSGroupController::class, 'create'])
            ->name('addCfsGroup');
        Route::post('add', [CFSGroupController::class, 'store']);
        Route::get('{cfsGroup}/edit', [CFSGroupController::class, 'edit'])
            ->name('editCfsGroup');
        Route::post('{cfsGroup}/edit', [CFSGroupController::class, 'update']);
        Route::match(['post', 'get'],
            '{cfsGroup}/delete', [CFSGroupController::class, 'destroy'])
            ->name('deleteCfsGroup');
    }
);


Route::group([
    'prefix' => 'cfs-items', 'middleware' =>['auth:web',PasswordExpired::class, 'permission:e-ref_books']
],
    function () {
        Route::get('add/{cfsGroup}', [CFSItemController::class, 'create'])
            ->name('addCfsItem');
        Route::post('add/{cfsGroup}', [CFSItemController::class, 'store']);
        Route::get('{cfsItem}/edit', [CFSItemController::class, 'edit'])
            ->name('editCfsItem');
        Route::post('{cfsItem}/edit', [CFSItemController::class, 'update']);
        Route::match(['post', 'get'],
            '{cfsItem}/delete', [CFSItemController::class, 'destroy'])
            ->name('deleteCfsItem');
    }
);