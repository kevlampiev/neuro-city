<?php

use App\DataServices\Task\TaskLinksDataservice;
use App\Http\Controllers\Task\MessageController;
use App\Http\Controllers\Task\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Task\TaskLinksController;

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

        Route::group([
            'middleware'=>['permission:s-agreements'],
        ],
            function () {
                Route::get('{task}/addAgreement', [TaskLinksController::class, 'chooseAgreementToAttach'])
                    ->name('attachAgreementToTask');
                Route::post('{task}/addAgreement', [TaskLinksController::class, 'attachAgreement']);
                Route::get('{task}/detachAgreement/{agreement}', [TaskLinksController::class, 'detachAgreement'])
                    ->name('detachAgreementFromTask');
            }
        );
    }
);



Route::group([
    'prefix' => 'messages'
],
    function () {
        Route::get('{message}/reply', [MessageController::class, 'createReply'])
            ->name('messageReply');
        Route::post('{message}/reply', [MessageController::class, 'store']);
        Route::get('{message}/edit', [MessageController::class, 'edit'])
            ->name('messageEdit');
        Route::post('{message}/edit', [MessageController::class, 'update']);
        Route::get('{message}/delete', [MessageController::class, 'delete'])
            ->name('messageDelete');
    }
);


Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
