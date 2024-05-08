<?php

use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Middleware\CheckIsAdmin;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth:web',CheckIsAdmin::class],
    'prefix' => 'users'
],
    function () {
        // Route::get('/', [UsersController::class, 'index'])
        //     ->name('users');
        // Route::get('add', [UsersController::class, 'add'])
        //     ->name('addUser');
        // Route::post('add', [UsersController::class, 'store']);
        // Route::get('{user}/edit', [UsersController::class, 'edit'])
        //     ->name('editUser');
        // Route::post('{user}/edit', [UsersController::class, 'update']);
        // Route::match(['post', 'get'], '{user}/delete', [UsersController::class, 'delete'])
        //     ->name('deleteUser');
        // Route::match(['get', 'post'], '{user}/setTmpPswd', [UsersController::class, 'setTempPassword'])
        //     ->name('setTempPassword');
        // Route::get('{user}/summary/{page?}', [UsersController::class, 'userSummary'])
        //     ->name('userSummary');

        // Route::get('{user}/addRole', [UsersController::class, 'addRole'])
        //     ->name('userAddRole');
        // Route::post('{user}/addRole', [UsersController::class, 'attachRole']);
        // Route::match(['get', 'post'],'{user}/detach-role/{role}', [UsersController::class, 'detachRole'])
        //     ->name('detachRoleFromUser');

        // Route::get('{user}/add-permission', [UsersController::class, 'addPermission'])
        //     ->name('userAddPermission');
        // Route::post('{user}/add-permission', [UsersController::class, 'attachPermission']);
        // Route::match(['get', 'post'],'{user}/detach-permission/{permission}', [UsersController::class, 'detachPermission'])
        //     ->name('detachPermissionFromUser');

    });
