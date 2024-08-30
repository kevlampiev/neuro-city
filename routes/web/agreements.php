<?php

use App\Http\Controllers\Agreement\AgreementController;
use App\Http\Controllers\Admin\AgreementNoteController;
use App\Http\Controllers\Admin\AgreementPaymentController;
use App\Http\Controllers\Agreement\AgreementTypeController; 
use App\Http\Controllers\Admin\AgreementKeywordsController;
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
        // Route::middleware('permission:e-agreement')->match(['get', 'post'], '{agreement}/add-vehicle', [AgreementController::class, 'addVehicle'])
        //     ->name('agreementAddVehicle');
        // Route::middleware('permission:e-agreement')->get('{agreement}/detach-vehicle/{vehicle}', [AgreementController::class, 'detachVehicle'])
        //     ->name('agreementDetachVehicle');
//                Платежи по договору
        // Route::middleware('permission:e-agreement')->get('{agreement}/add-payment', [AgreementPaymentController::class, 'create'])
        //     ->name('addAgrPayment');
        // Route::middleware('permission:e-agreement')->post('{agreement}/add-payment', [AgreementPaymentController::class, 'store']);
        // Route::middleware('permission:e-agreement')->get('{agreement}/add-massive-payments', [AgreementPaymentController::class, 'createAddPayments'])
        //     ->name('massAddPayments');
        // Route::middleware('permission:e-agreement')->post('{agreement}/add-massive-payments', [AgreementPaymentController::class, 'storeAddPayments']);
        // Route::middleware('permission:e-agreement')->get('{agreement}/edit-payment/{payment}', [AgreementPaymentController::class, 'edit'])
        //     ->name('editAgrPayment');
        // Route::middleware('permission:e-agreement')->post('{agreement}/edit-payment/{payment}', [AgreementPaymentController::class, 'update']);
        // Route::middleware('permission:e-agreement')->post('{agreement}/delete-payments', [AgreementPaymentController::class, 'massDeletePayments'])
        //     ->name('massDeletePayments');
        // Route::middleware('permission:e-real_payment')->get('{agreement}/movetoreal/{payment}', [AgreementPaymentController::class, 'toRealPayments'])
        //     ->name('movePaymentToReal');
        // Route::middleware('permission:e-agreement')->match(['get', 'post'], '{agreement}/delete-payment/{payment}', [AgreementPaymentController::class, 'delete'])
        //     ->name('deleteAgrPayment');
//              Real payments
        // Route::middleware('permission:e-real_payment')->get('{agreement}/add-real-payment', [RealPaymentController::class, 'create'])
        //     ->name('addRealPayment');
        // Route::middleware('permission:e-real_payment')->post('{agreement}/add-real-payment', [RealPaymentController::class, 'store']);
        // Route::middleware('permission:e-real_payment')->get('{agreement}/edit-real-payment/{payment}', [RealPaymentController::class, 'edit'])
        //     ->name('editRealPayment');
        // Route::middleware('permission:e-real_payment')->post('{agreement}/edit-real-payment/{payment}', [RealPaymentController::class, 'update']);
        // Route::middleware('permission:e-real_payment')->match(['get', 'post'], '{agreement}/delete-real-payment/{payment}', [RealPaymentController::class, 'delete'])
        //     ->name('deleteRealPayment');

//         Route::group(['prefix' => 'notes', 'middleware' =>'permission:e-agreement'],
//             function () {
//                 Route::get('add/{agreement}', [AgreementNoteController::class, 'create'])
//                     ->name('addAgreementNote');
//                 Route::post('add/{agreement}', [AgreementNoteController::class, 'store']);
//                 Route::get('edit/{agreementNote}', [AgreementNoteController::class, 'edit'])
//                     ->name('editAgreementNote');
//                 Route::post('edit/{agreementNote}', [AgreementNoteController::class, 'update']);
//                 Route::get('delete/{agreementNote}', [AgreementNoteController::class, 'erase'])
//                     ->name('deleteAgreementNote');
//             });

//         Route::group(['prefix' => 'keywords', 'middleware' =>'permission:e-agreement'],
//         function () {
//             Route::get('add/{agreement}', [AgreementKeywordsController::class, 'create'])
//                 ->name('addAgreementKeyword');
//             Route::post('add/{agreement}', [AgreementKeywordsController::class, 'store']);
//             Route::get('edit/{agreementKeyword}', [AgreementKeywordsController::class, 'edit'])
//                 ->name('editAgreementKeyword');
//             Route::post('edit/{agreementKeyword}', [AgreementKeywordsController::class, 'update']);
//             Route::get('delete/{agreementKeyword}', [AgreementKeywordsController::class, 'erase'])
//                 ->name('deleteAgreementKeyword');
//         });


    });


    Route::group([
        'prefix' => 'documents', 'middleware' => 'permission:e-agreements'
        ],
        function () {
    
            Route::get('add/agreement/{agreement}', [DocumentController::class, 'createAgreementDocument'])
                ->name('addAgreementDocument');
            Route::post('add/agreement/{agreement}', [DocumentController::class, 'storeAgreementDocument']);
            // Route::get('edit/agreement/{document}', [DocumentController::class, 'edit'])
            //     ->name('editAgreementDocument');
            // Route::post('edit/agreement/{document}', [DocumentController::class, 'update']);
            
        });