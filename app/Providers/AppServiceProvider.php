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
            $tmpCachePath = '/tmp/cache';
            $tmpSessionsPath = '/tmp/sessions';
    
            if (!File::exists($tmpCachePath)) {
                File::makeDirectory($tmpCachePath, 0755, true);
            }
    
            if (!File::exists($tmpSessionsPath)) {
                File::makeDirectory($tmpSessionsPath, 0755, true);
            }
    

            config(['cache.stores.file.path' => $tmpCachePath]);
            config(['session.files' => $tmpSessionsPath]);
        }

        Paginator::useBootstrap();
    }
}
