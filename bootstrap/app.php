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

// Ensure necessary directories exist and are writable in Vercel's temporary storage
$cachePath = '/tmp/cache';
$storagePath = '/tmp/storage';
$viewsPath = '/tmp/views';
$sessionsPath = '/tmp/sessions';

// Create the directories if they don't exist
foreach ([$cachePath, $storagePath, $viewsPath, $sessionsPath] as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

// Override paths for serverless environments (like Vercel)
$app->bind('path.cache', function () use ($cachePath) {
    return $cachePath; // Cache path in the temporary directory
});

$app->bind('path.storage', function () use ($storagePath) {
    return $storagePath; // Storage path in the temporary directory
});

$app->bind('path.sessions', function () use ($sessionsPath) {
    return $sessionsPath; // Sessions path in the temporary directory
});

// Explicitly set cache store path in config (for file caching)
config(['cache.stores.file.path' => $cachePath]);

// Explicitly set the views path (use /tmp for views if necessary)
$app->bind('path.views', function () use ($viewsPath) {
    return $viewsPath; // Views path in the temporary directory
});

// Return the application instance
return $app;
