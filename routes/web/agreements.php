<?php

use App\Http\Controllers\Agreement\AgreementController;
use App\Http\Controllers\Agreement\AgreementNoteController;
use App\Http\Controllers\Agreement\AgreementTypeController;
use App\Http\Controllers\Agreement\AgreementDocumentController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasswordExpired;
use App\Http\Middleware\CheckSuperuser;


Route::group([
    'prefix' => 'agrTypes',
    'middleware' => ['auth:web',PasswordExpired::class, CheckSuperuser::class],
],
    function () {
        Route::middleware('permission:e-ref_books')->get('/', [AgreementTypeController::class, 'index'])
            ->name('agrTypes');
        Route::middleware('permission:e-ref_books')->get('add', [AgreementTypeController::class, 'create'])
            ->name('addAgrType');
        Route::middleware('permission:e-ref_books')->post('add', [AgreementTypeController::class, 'store']);
        Route::middleware('permission:e-ref_books')->get('{agrType}/edit', [AgreementTypeController::class, 'edit'])
            ->name('editAgrType');
        Route::middleware('permission:e-ref_books')->post('{agrType}/edit', [AgreementTypeController::class, 'update']);
        Route::middleware('permission:e-ref_books')->match(['post', 'get'],
            '{agrType}/delete', [AgreementTypeController::class, 'destroy'])
            ->name('deleteAgrType');
    }
);

Route::group([
    'prefix' => 'agreements',
    'middleware' => ['auth:web',PasswordExpired::class],
],
    function () {
        Route::middleware('permission:s-agreements')->get('/list', [AgreementController::class, 'index'])
            ->name('agreements');
        Route::middleware('permission:e-agreements')->get('add', [AgreementController::class, 'create'])
            ->name('addAgreement');
        Route::middleware('permission:e-agreements')->post('add', [AgreementController::class, 'store']);
        Route::middleware('permission:e-agreements')->get('{agreement}/edit', [AgreementController::class, 'edit'])
            ->name('editAgreement');
        Route::middleware('permission:e-agreements')->post('{agreement}/edit', [AgreementController::class, 'update']);
        Route::middleware('permission:e-agreements')->match(['post', 'get'], '{agreement}/delete', [AgreementController::class, 'delete'])
            ->name('deleteAgreement');
        Route::middleware('permission:s-agreements')->get('{agreement}/summary/{page?}', [AgreementController::class, 'summary'])
            ->name('agreementSummary');
       
    }
);

Route::group([
    'prefix' => 'agreement-notes',
    'middleware' => ['auth:web',PasswordExpired::class,'permission:s-agreements'],
    ],
    function() {
    Route::get('{agreement}/notes/add', [AgreementNoteController::class, 'create'])->name('addAgreementNote');
    Route::post('{agreement}/notes/add', [AgreementNoteController::class, 'store']);
    Route::get('{agreement}/notes/edit/{agreementNote}', [AgreementNoteController::class, 'edit'])->name('editAgreementNote');
    Route::post('{agreement}/notes/edit/{agreementNote}', [AgreementNoteController::class, 'update']);
    Route::match(['get', 'post'], '/notes/delete/{agreementNote}', [AgreementNoteController::class, 'delete'])->name('deleteAgreementNote');
    }
);

Route::group([
    'prefix' => 'documents', 'middleware' => 'permission:e-agreements'
    ],
    function () {

        Route::get('agreement/{agreement}/addDocument', [AgreementDocumentController::class, 'createSingeDocument'])
            ->name('addAgreementDocument');
        Route::post('agreement/{agreement}/addDocument', [AgreementDocumentController::class, 'storeSingle']);     
        
        Route::get('agreement/{agreement}/addManyDocument', [AgreementDocumentController::class, 'createMultipleDocuments'])
            ->name('addAgreementManyDocument');
        Route::post('agreement/{agreement}/addManyDocument', [AgreementDocumentController::class, 'storeMultiple']);             
        
        Route::match(['get', 'post'],'agreement/{agreement}/document/{document}/detach', [AgreementDocumentController::class, 'detach'])
        ->name('detachAgreementDocument');          
    }
);