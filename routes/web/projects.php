<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectNoteController;
use App\Http\Middleware\PasswordExpired;


Route::middleware(['auth:web',PasswordExpired::class,'permission:s-projects'])->group(function () {
    Route::get('projects', [ProjectController::class, 'index'])
    ->name('projects.index');
    Route::get('project/{project}/summary/{page?}', [ProjectController::class, 'summary'])->name('projects.summary');    
});

Route::middleware(['auth:web',PasswordExpired::class,'permission:e-projects'])->group(function () {
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});


Route::group([
    'prefix' => 'project-notes',
    'middleware' => ['auth:web',PasswordExpired::class,'permission:s-projects'],
    ],
    function() {
    Route::get('{project}/notes/add', [ProjectNoteController::class, 'create'])->name('addProjectNote');
    Route::post('{project}/notes/add', [ProjectNoteController::class, 'store']);
    Route::get('{project}/notes/edit/{projectNote}', [ProjectNoteController::class, 'edit'])->name('editProjectNote');
    Route::post('{project}/notes/edit/{projectNote}', [ProjectNoteController::class, 'update']);
    Route::match(['get', 'post'], '/notes/delete/{projectNote}', [ProjectNoteController::class, 'delete'])->name('deleteProjectNote');
    }
);