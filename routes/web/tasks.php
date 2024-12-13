<?php

use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Task\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;


Route::group([
    'prefix' => 'tasks',
    'middleware'=>['auth', PasswordExpired::class],
],
    function () {

        Route::get('{task}/card/{page?}', [TaskController::class, 'viewTaskCard'])
            ->name('taskCard');
        Route::get('{user}/user-tasks', [TaskController::class, 'viewUserTasks'])
            ->name('userTasks');
        Route::get('addTask/{parentTask?}', [TaskController::class, 'createSubTask'])
            ->name('addTask');
        Route::post('addTask/{parentTask?}', [TaskController::class, 'store']);
        Route::get('/add-task-for-agreement/{agreement}', [TaskController::class, 'createTaskForAgreement'])
            ->name('addTaskForAgreement');
        Route::get('/add-task-for-vehicle/{vehicle}', [TaskController::class, 'createTaskForVehicle'])
            ->name('addTaskForVehicle');
        Route::get('{task}/edit', [TaskController::class, 'edit'])
            ->name('editTask');
        Route::post('{task}/edit', [TaskController::class, 'update']);
        Route::get('{task}/complete', [TaskController::class, 'markAsDone'])
            ->name('markTaskAsDone');
        Route::get('{task}/cancel', [TaskController::class, 'markAsCanceled'])
            ->name('markTaskAsCanceled');
        Route::get('{task}/restore', [TaskController::class, 'markAsRunning'])
            ->name('markTaskAsRunning');
        Route::get('{task}/setImportance/{importance}', [TaskController::class, 'setImportance'])
            ->name('setTaskImportance');
        Route::get('{task}/addMessage', [TaskController::class, 'addMessage'])
            ->name('addTaskMessage');
        Route::post('{task}/addMessage', [TaskController::class, 'storeMessage']);
        Route::get('{task}/addDocument', [TaskController::class, 'addDocument'])
            ->name('addTaskDocument');
        Route::post('{task}/addDocument', [TaskController::class, 'storeDocument']);
        Route::get('{task}/addFollower', [TaskController::class, 'addFollower'])
            ->name('addTaskFollower');
        Route::post('{task}/addFollower', [TaskController::class, 'storeFollower']);
        Route::get('{task}/detachFollower/{user}', [TaskController::class, 'detachFollower'])
            ->name('detachTaskFollower');

    }
);
