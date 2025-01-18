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

// Ensure necessary directories exist and are writable
if (!is_dir('/tmp/cache')) {
    mkdir('/tmp/cache', 0777, true);
}

if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0777, true);
}

if (!is_dir('/tmp/storage')) {
    mkdir('/tmp/storage', 0777, true);
}

if (!is_dir('/tmp/sessions')) {
    mkdir('/tmp/sessions', 0777, true);
}

// Override paths for serverless environments (like Vercel)
$app->bind('path.cache', function () {
    return '/tmp/cache'; // Cache path in the temporary directory
});

$app->bind('path.storage', function () {
    return '/tmp/storage'; // Storage path in the temporary directory
});

$app->bind('path.sessions', function () {
    return '/tmp/sessions'; // Sessions path in the temporary directory
});

// Dynamically override the cache path
$app->bind('path.cache', function () {
    return '/tmp/cache';
});

// Ensure cache path is writable and exists
if (!is_dir('/tmp/cache')) {
    mkdir('/tmp/cache', 0777, true);
}

// Dynamically override the storage path
$app->useStoragePath(env('APP_STORAGE', '/tmp/storage'));

// Dynamically override the views path
$app->bind('path.views', function () {
    return '/tmp/views';
});

return $app;
