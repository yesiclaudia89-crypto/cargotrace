<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS in production, Vercel, or when behind an SSL reverse proxy
        if (app()->environment('production') 
            || isset($_ENV['VERCEL']) 
            || isset($_SERVER['VERCEL']) 
            || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
