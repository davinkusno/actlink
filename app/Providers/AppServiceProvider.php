<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            // Set writable paths for cache and logs
            $tmpCachePath = '/tmp/cache';
            $tmpSessionsPath = '/tmp/sessions';
            $tmpLogPath = '/tmp/logs';
    
            // Make directories if they don't exist
            File::makeDirectory($tmpCachePath, 0755, true);
            File::makeDirectory($tmpSessionsPath, 0755, true);
            File::makeDirectory($tmpLogPath, 0755, true);
    
            // Update config to use these paths
            config(['cache.stores.file.path' => $tmpCachePath]);
            config(['session.files' => $tmpSessionsPath]);
            config(['log.channel' => 'custom']);
            config(['logging.channels.custom' => [
                'driver' => 'single',
                'path' => $tmpLogPath . '/laravel.log',
                'level' => 'debug',
            ]]);
        }
    
        Paginator::useBootstrap();
    }
}
