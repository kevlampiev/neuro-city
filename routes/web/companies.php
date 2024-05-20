<?php

use App\Http\Controllers\Company\CompanyController;
// use App\Http\Controllers\Admin\PowerOfAttorneyController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckSuperuser;
use App\Http\Middleware\PasswordExpired;


Route::group([
    'prefix' => 'companies', 'middleware' => ['auth:web',PasswordExpired::class, CheckSuperuser::class]
],
    function () {
        Route::get('/', [CompanyController::class, 'index'])
            ->name('companies');
        Route::get('add', [CompanyController::class, 'create'])
            ->name('addCompany');
        Route::post('add', [CompanyController::class, 'store']);
        Route::get('{company}/edit', [CompanyController::class, 'edit'])
            ->name('editCompany');
        Route::post('{company}/edit', [CompanyController::class, 'update']);
        Route::match(['post', 'get'],
            '{company}/delete', [CompanyController::class, 'destroy'])
            ->name('deleteCompany');
        Route::get('{company}/summary/{page?}', [CompanyController::class, 'summary'])
            ->name('companySummary');
    }
);


// Route::group(
//     ['prefix' => 'poa', 'middleware' =>'permission:e-agreement'],
//     function () {
//         Route::get('{company}/add', [PowerOfAttorneyController::class, 'create'])
//             ->name('addPOA');
//         Route::post('{company}/add', [PowerOfAttorneyController::class, 'store']);
//         Route::get('{powerOfAttorney}/edit', [PowerOfAttorneyController::class, 'edit'])
//             ->name('editPOA');
//         Route::post('{powerOfAttorney}/edit', [PowerOfAttorneyController::class, 'update']);
//         Route::match(['get', 'post'], '{powerOfAttorney}/delete', [PowerOfAttorneyController::class, 'erase'])
//             ->name('deletePOA');
//     }
// );
