<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('database:backup --keep=14')
            ->dailyAt('01:15')
            ->timezone('Africa/Accra')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/database-backup.log'));

        $schedule->command('files:backup')
            ->weeklyOn(0, '02:00')
            ->timezone('Africa/Accra')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/files-backup.log'));
    })->create();