<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Middleware\PasswordExpired;

Route::resource('projects', ProjectController::class);
