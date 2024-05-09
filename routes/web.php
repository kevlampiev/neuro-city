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

Auth::routes(['reset'=>false]);
