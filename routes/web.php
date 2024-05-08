<?php

use App\Http\Requests\UserProfileRequest;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\UserProfileController;


Route::prefix('')->group(function () {
    $files = glob(base_path('routes/web/*.php'));
    foreach ($files as $file) {
        require $file;
    }
});

Auth::routes();

// Route::get('/', function () {
//     return view('main');
// });
// Route::get('/counter', Counter::class);

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/user-profile', [UserProfileController::class, 'edit'])->name('user.profileEdit');
// Route::post('/user-profile', [UserProfileController::class, 'update']);
