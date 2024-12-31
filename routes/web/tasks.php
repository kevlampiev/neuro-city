<?php

use App\Http\Controllers\Task\MessageController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\TaskLinksController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;
use App\Http\Controllers\Task\TaskDocumentController;

Route::group([
    'prefix' => 'tasks',
    'middleware' => ['auth', PasswordExpired::class],
], function () {
    Route::controller(TaskController::class)->group(function () {
        Route::get('{task}/card/{page?}', 'viewTaskCard')->name('taskCard');
        Route::get('{user}/user-tasks', 'viewUserTasks')->name('userTasks');
        Route::get('{user}/user-task-list', 'viewUserTaskList')->name('userTaskList');
        Route::get('addTask/{parentTask?}', 'createSubTask')->name('addTask');
        Route::post('addTask/{parentTask?}', 'store');

        Route::middleware(['task.manager', 'task.isRunning'])->group(function () {
            Route::get('{task}/edit', 'edit')->name('editTask');
            Route::post('{task}/edit', 'update');
            Route::get('{task}/complete', 'markAsDone')->name('markTaskAsDone');
            Route::get('{task}/cancel', 'markAsCanceled')->name('markTaskAsCanceled');
            Route::get('{task}/setImportance/{importance}', 'setImportance')->name('setTaskImportance');
            Route::get('{task}/addFollower', 'addFollower')->name('addTaskFollower');
            Route::post('{task}/addFollower', 'storeFollower');
            Route::get('{task}/detachFollower/{user}', 'detachFollower')->name('detachTaskFollower');
        });

        Route::middleware(['task.manager', 'task.isClosed'])->get('{task}/restore', 'markAsRunning')->name('markTaskAsRunning');

        Route::middleware(['task.interessant', 'task.isRunning'])->group(function () {
            Route::get('{task}/addMessage', 'addMessage')->name('addTaskMessage');
            Route::post('{task}/addMessage', 'storeMessage');
            Route::get('{task}/addDocument', 'addDocument')->name('addTaskDocument');
            Route::post('{task}/addDocument', 'storeDocument');
        });

        Route::group(['middleware' => ['permission:s-agreements']], function () {
            Route::controller(TaskLinksController::class)->group(function () {
                Route::middleware(['task.manager', 'task.isRunning'])->group(function () {
                    Route::get('{task}/addAgreement', 'chooseAgreementToAttach')->name('attachAgreementToTask');
                    Route::post('{task}/addAgreement', 'attachAgreement');
                    Route::get('{task}/detachAgreement/{agreement}', 'detachAgreement')->name('detachAgreementFromTask');
                });
                Route::get('/add-task-for-agreement/{agreement_id}', [TaskController::class, 'createSubTask'])->name('addTaskForAgreement');
                Route::post('/add-task-for-agreement/{agreement_id}', [TaskController::class, 'store']);
            });
        });

        Route::group(['middleware' => ['permission:s-counterparty']], function () {
            Route::controller(TaskLinksController::class)->group(function () {
                Route::middleware(['task.manager', 'task.isRunning'])->group(function () {
                    Route::get('{task}/addCompany', 'chooseCompanyToAttach')->name('attachCompanyToTask');
                    Route::post('{task}/addCompany', 'attachCompany');
                    Route::get('{task}/detachCompany/{company}', 'detachCompany')->name('detachCompanyFromTask');
                });
                Route::get('/add-task-for-company/{company_id}', [TaskController::class, 'createSubTask'])->name('addTaskForCompany');
                Route::post('/add-task-for-company/{company_id}', [TaskController::class, 'store']);
            });
        });
    });
});

Route::group([
    'prefix' => 'messages',
    'middleware' => ['auth', PasswordExpired::class],
], function () {
    Route::controller(MessageController::class)->group(function () {
        Route::get('{message}/reply', 'createReply')->name('messageReply');
        Route::post('{message}/reply', 'store');
        Route::get('{message}/edit', 'edit')->name('messageEdit');
        Route::post('{message}/edit', 'update');
        Route::get('{message}/delete', 'delete')->name('messageDelete');
    });
});

Route::middleware(['auth', PasswordExpired::class])->get(
    '/notifications/{id}/mark-as-read',
    [NotificationController::class, 'markAsRead']
)->name('notifications.markAsRead');

Route::group([
    'prefix' => 'documents', 'middleware' => ['task.interessant', 'task.isRunning'],
    ],
    function () {

        Route::get('task/{task}/addDocument', [TaskDocumentController::class, 'createSingeDocument'])
            ->name('addTaskDocument');
        Route::post('task/{task}/addDocument', [TaskDocumentController::class, 'storeSingle']);     
        
        Route::get('task/{task}/addManyDocument', [TaskDocumentController::class, 'createMultipleDocuments'])
            ->name('addTaskManyDocuments');
        Route::post('task/{task}/addManyDocument', [TaskDocumentController::class, 'storeMultiple']);             
        
        Route::match(['get', 'post'],'task/{task}/document/{document}/detach', [TaskDocumentController::class, 'detach'])
        ->name('detachTaskDocument');          
    }
);