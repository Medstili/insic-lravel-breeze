<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CoachMiddleware;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Console\Scheduling\Schedule;


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
        //
    })->create();

// Add your scheduling logic:
// app()->resolving(Schedule::class, function (Schedule $schedule) {
//     // Run the suggested appointments storage command every Monday at 00:05
//     $schedule->command('app:store-suggestions')->weeklyOn(5, '15:51');
//     // Notify if patients did not complete their weekly quota every Monday at 00:10
//     $schedule->command('app:notify-incomplete-weekly-quota')->weeklyOn(5,'15:35');

// });

return $app;

