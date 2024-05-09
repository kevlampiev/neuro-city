<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Middleware\CheckIsAdmin;
use App\Http\Middleware\CheckSuperuser;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth:web',CheckSuperuser::class],
    'prefix' => 'users'
],
    function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('users');
        Route::get('add', [UserController::class, 'add'])
            ->name('addUser');
        Route::post('add', [UserController::class, 'store']);
        Route::get('{user}/edit', [UserController::class, 'edit'])
            ->name('editUser');
        Route::post('{user}/edit', [UserController::class, 'update']);
        Route::match(['post', 'get'], '{user}/delete', [UserController::class, 'delete'])
            ->name('deleteUser');
        Route::match(['get', 'post'], '{user}/setTmpPswd', [UserController::class, 'setTempPassword'])
            ->name('setTempPassword');
        Route::get('{user}/summary/{page?}', [UserController::class, 'userSummary'])
            ->name('userSummary');

        Route::get('{user}/addRole', [UserController::class, 'addRole'])
            ->name('userAddRole');
        Route::post('{user}/addRole', [UserController::class, 'attachRole']);
        Route::match(['get', 'post'],'{user}/detach-role/{role}', [UserController::class, 'detachRole'])
            ->name('detachRoleFromUser');

        Route::get('{user}/add-permission', [UserController::class, 'addPermission'])
            ->name('userAddPermission');
        Route::post('{user}/add-permission', [UserController::class, 'attachPermission']);
        Route::match(['get', 'post'],'{user}/detach-permission/{permission}', [UserController::class, 'detachPermission'])
            ->name('detachPermissionFromUser');

    });
