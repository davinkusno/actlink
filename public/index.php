<?php

// Autoload dependencies
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables if not already loaded
if (file_exists(__DIR__ . '/../.env')) {
    Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->safeLoad();
}

// Bootstrap Laravel
$app = require __DIR__ . '/../bootstrap/app.php';

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);