<?php

use App\Http\Middleware\CheckMember;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(CheckMember::class);
        $middleware->web([
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

// Dynamically override the cache path
$app->bind('path.cache', function () {
    return '/tmp';
});

// Ensure cache path is writable and exists
if (!is_dir('/tmp')) {
    mkdir('/tmp', 0777, true);
}

return $app;
