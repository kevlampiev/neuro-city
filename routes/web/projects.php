<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Middleware\PasswordExpired;

Route::resource('projects', ProjectController::class);

Route::get('project/{project}/summary', [ProjectController::class, 'summary'])->name('projects.summary');
