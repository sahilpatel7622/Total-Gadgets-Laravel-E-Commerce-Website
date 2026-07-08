<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckUserStatus;
use App\Http\Middleware\MaintenanceMode;
use App\Http\Middleware\NoCache;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'check.status' => CheckUserStatus::class,
            'nocache' => NoCache::class,
        ]);

        $middleware->web(append: [
            MaintenanceMode::class, NoCache::class,
        ]);
    })

    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
