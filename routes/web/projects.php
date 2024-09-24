<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Middleware\PasswordExpired;


Route::middleware(['auth:web',PasswordExpired::class,'permission:s-projects'])->group(function () {
    Route::get('projects', [ProjectController::class, 'index'])
    ->name('projects.index');
    Route::get('project/{project}/summary', [ProjectController::class, 'summary'])->name('projects.summary');    
});

Route::middleware(['auth:web',PasswordExpired::class,'permission:e-projects'])->group(function () {
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});