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

// Dynamically override the cache and storage paths for serverless environment
$app->bind('path.cache', function () {
    return '/tmp/cache'; // Cache path in the temporary directory
});

$app->bind('path.storage', function () {
    return '/tmp/storage'; // Storage path in the temporary directory
});

$app->bind('path.sessions', function () {
    return '/tmp/sessions'; // Sessions path in the temporary directory
});

// Ensure the directories are created
if (!is_dir('/tmp/cache')) {
    mkdir('/tmp/cache', 0777, true);
}

if (!is_dir('/tmp/storage')) {
    mkdir('/tmp/storage', 0777, true);
}

if (!is_dir('/tmp/sessions')) {
    mkdir('/tmp/sessions', 0777, true);
}

// Explicitly set the configuration for cache path in cache store
config(['cache.stores.file.path' => '/tmp/cache']);
config(['session.files' => '/tmp/sessions']); // For session storage
config(['filesystems.disks.local.root' => '/tmp/storage']); // Local disk root path for filesystem

return $app;
