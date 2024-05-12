<?php

use App\Http\Controllers\User\RoleController;
use App\Http\Middleware\CheckSuperuser;
use App\Http\Middleware\PasswordExpired;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'roles',
    'middleware' => ['auth:web',PasswordExpired::class, CheckSuperuser::class],
],
    function () {
        Route::get('/', [RoleController::class, 'index'])
            ->name('roles');
        Route::get('add', [RoleController::class, 'create'])
            ->name('addRole');
        Route::post('add', [RoleController::class, 'store']);
        Route::get('{role}/edit', [RoleController::class, 'edit'])
            ->name('editRole');
        Route::post('{role}/edit', [RoleController::class, 'update']);
        Route::match(['post', 'get'], '{role}/delete', [RoleController::class, 'delete'])
            ->name('deleteRole');
        Route::get('{role}/summary/{page?}', [RoleController::class, 'roleSummary'])
            ->name('roleSummary');
        Route::get('{role}/attach-user', [RoleController::class, 'addUser'])
            ->name('roleAttachUser');
        Route::post('{role}/attach-user', [RoleController::class, 'storeUser']);
        Route::match(['get', 'post'],'{role}/detach-user/{user}', [RoleController::class, 'detachUser'])
        ->name('roleDetachUser');
        Route::get('{role}/attach-permission', [RoleController::class, 'addPermission'])
            ->name('roleAttachPermission');
        Route::post('{role}/attach-permission', [RoleController::class, 'storePermission']);
        Route::match(['get', 'post'],'{role}/detach-permission/{permission}',
            [RoleController::class, 'detachPermission'])
        ->name('roleDetachPermission');

    });
