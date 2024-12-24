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
    'middleware'=>['auth', PasswordExpired::class ],
],
    function () {
        Route::get('{task}/card/{page?}', [TaskController::class, 'viewTaskCard'])
            ->name('taskCard');
        Route::get('{user}/user-tasks', [TaskController::class, 'viewUserTasks'])
            ->name('userTasks');
        Route::get('addTask/{parentTask?}', [TaskController::class, 'createSubTask'])
            ->name('addTask');
        Route::post('addTask/{parentTask?}', [TaskController::class, 'store']);
        
        Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/edit', [TaskController::class, 'edit'])
            ->name('editTask');
        Route::middleware(['task.manager', 'task.isRunning'])->post('{task}/edit', [TaskController::class, 'update']);
        Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/complete', [TaskController::class, 'markAsDone'])
            ->name('markTaskAsDone');
        Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/cancel', [TaskController::class, 'markAsCanceled'])
            ->name('markTaskAsCanceled');
        Route::middleware(['task.manager', 'task.isClosed'])->get('{task}/restore', [TaskController::class, 'markAsRunning'])
            ->name('markTaskAsRunning');
        Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/setImportance/{importance}', [TaskController::class, 'setImportance'])
            ->name('setTaskImportance');
        Route::middleware(['task.interessant', 'task.isRunning'])->get('{task}/addMessage', [TaskController::class, 'addMessage'])
            ->name('addTaskMessage');
        Route::middleware(['task.interessant', 'task.isRunning'])->post('{task}/addMessage', [TaskController::class, 'storeMessage']);
        Route::middleware(['task.interessant', 'task.isRunning'])->get('{task}/addDocument', [TaskController::class, 'addDocument'])
            ->name('addTaskDocument');
        Route::middleware(['task.interessant', 'task.isRunning'])->post('{task}/addDocument', [TaskController::class, 'storeDocument']);
        
        Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/addFollower', [TaskController::class, 'addFollower'])
            ->name('addTaskFollower');
        Route::middleware(['task.manager', 'task.isRunning'])->post('{task}/addFollower', [TaskController::class, 'storeFollower']);
        Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/detachFollower/{user}', [TaskController::class, 'detachFollower'])
            ->name('detachTaskFollower');


        Route::group([
            'middleware'=>['permission:s-agreements'],
        ],
            function () {
                Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/addAgreement', [TaskLinksController::class, 'chooseAgreementToAttach'])
                    ->name('attachAgreementToTask');
                Route::middleware(['task.manager', 'task.isRunning'])->post('{task}/addAgreement', [TaskLinksController::class, 'attachAgreement']);
                Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/detachAgreement/{agreement}', [TaskLinksController::class, 'detachAgreement'])
                    ->name('detachAgreementFromTask');
                Route::get('/add-task-for-agreement/{agreement_id}', [TaskController::class, 'createSubTask'])
                    ->name('addTaskForAgreement');
                Route::post('/add-task-for-agreement/{agreement_id}', [TaskController::class, 'store']);
            
            }
        );

        Route::group([
            'middleware'=>['permission:s-counterparty'],
        ],
            function () {
                Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/addCompany', [TaskLinksController::class, 'chooseCompanyToAttach'])
                    ->name('attachCompanyToTask');
                Route::middleware(['task.manager', 'task.isRunning'])->post('{task}/addCompany', [TaskLinksController::class, 'attachCompany']);
                Route::middleware(['task.manager', 'task.isRunning'])->get('{task}/detachCompany/{company}', [TaskLinksController::class, 'detachCompany'])
                    ->name('detachCompanyFromTask');
                Route::get('/add-task-for-company/{company_id}', [TaskController::class, 'createSubTask'])
                    ->name('addTaskForCompany');
                Route::post('/add-task-for-company/{company_id}', [TaskController::class, 'store']);
    
            }
        );

    }
);



Route::group([
    'prefix' => 'messages',
    'middleware'=>['auth', PasswordExpired::class],
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


Route::middleware(['auth', PasswordExpired::class ])->get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
