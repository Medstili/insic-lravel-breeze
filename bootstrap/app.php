<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CoachMiddleware;
use App\Http\Middleware\AdminMiddleware;



$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'coach' => CoachMiddleware::class,
            'admin' => AdminMiddleware::class,
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();


return $app;

