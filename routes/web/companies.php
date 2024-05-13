<?php


use App\Http\Controllers\Counterparty\CounterpartyController;
use App\Http\Controllers\Counterparty\CounterpartyEmployeeController;
use App\Http\Controllers\Counterparty\CounterpartyNoteController;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'counterparties', 'middleware' => 'permission:s-counterparty'
],
    function () {
        Route::get('/', [CounterpartyController::class, 'index'])
            ->name('counterparties');
        Route::get('{counterparty}/summary/{page?}', [CounterpartyController::class, 'summary'])
            ->name('counterpartySummary');
    }
);

Route::group([
    'prefix' => 'counterparties', 'middleware' => 'permission:e-counterparty'
],
    function () {
        Route::get('add', [CounterpartyController::class, 'create'])
            ->name('addCounterparty');
        Route::post('add', [CounterpartyController::class, 'store']);
        Route::get('{counterparty}/edit', [CounterpartyController::class, 'edit'])
            ->name('editCounterparty');
        Route::post('{counterparty}/edit', [CounterpartyController::class, 'update']);
        Route::match(['post', 'get'],
            '{counterparty}/delete', [CounterpartyController::class, 'destroy'])
            ->name('deleteCounterparty');
        
    }
);



Route::group([
    'prefix' => 'counterparty-staff', 'middleware' => 'permission:e-counterparty'
],
    function () {
        Route::get('{counterparty}/add', [CounterpartyEmployeeController::class, 'create'])
            ->name('addCounterpartyEmployee');
        Route::post('{counterparty}/add', [CounterpartyEmployeeController::class, 'store']);
        Route::get('{employee}/edit', [CounterpartyEmployeeController::class, 'edit'])
            ->name('editCounterpartyEmployee');
        Route::post('{employee}/edit', [CounterpartyEmployeeController::class, 'update']);
        Route::match(['post', 'get'],
            '{employee}/delete', [CounterpartyEmployeeController::class, 'destroy'])
            ->name('deleteCounterpartyEmployee');

    }
);

Route::group([
    'prefix' => 'counterparty-notes', 'middleware' => 'permission:e-counterparty'
],
    function () {
        Route::get('{counterparty}/add', [CounterpartyNoteController::class, 'create'])
            ->name('addCounterpartyNote');
        Route::post('{counterparty}/add', [CounterpartyNoteController::class, 'store']);
        Route::get('{counterpartyNote}/edit', [CounterpartyNoteController::class, 'edit'])
            ->name('editCounterpartyNote');
        Route::post('{counterpartyNote}/edit', [CounterpartyNoteController::class, 'update']);
        Route::match(['post', 'get'],
            '{counterpartyNote}/delete', [CounterpartyNoteController::class, 'destroy'])
            ->name('deleteCounterpartyNote');

    }
);
