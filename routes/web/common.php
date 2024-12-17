<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::get('/notifications/refresh', [NotificationController::class, 'refreshInfo'])->name('notifications.refresh');