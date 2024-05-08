<?php

use App\Http\Requests\UserProfileRequest;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\UserProfileController;


Route::group(['middleware'=>'auth'], function () {
    Route::get('/', function () {
        return view('main');
    });
    Route::get('/counter', Counter::class);
    
    
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::get('/user-profile', [UserProfileController::class, 'edit'])->name('user.profileEdit');
    Route::post('/user-profile', [UserProfileController::class, 'update']);
    

});
