<?php

use App\Http\Middleware\CheckTaskInteressant;
use App\Http\Middleware\CheckTaskIsClosed;
use App\Http\Middleware\CheckTaskIsRunning;
use App\Http\Middleware\CheckTaskManager;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\PasswordExpired;
use App\Http\Middleware\TaskAccessControl;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(RoleMiddleware::class);
        // $middleware->append(PermissionMiddleware::class);
        // $middleware->append(PasswordExpired::class);
        $middleware->alias([
            'permission' => PermissionMiddleware::class,
            'task.access' => TaskAccessControl::class,
            'task.manager' => CheckTaskManager::class,
            'task.performer' => CheckTaskManager::class,
            'task.interessant' =>CheckTaskInteressant::class,
            'task.isRunning' => CheckTaskIsRunning::class,
            'task.isClosed' => CheckTaskIsClosed::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
