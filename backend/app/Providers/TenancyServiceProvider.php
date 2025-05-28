<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use App\Http\Middleware\TenantMiddleware; // Commented out

class TenancyServiceProvider extends ServiceProvider
{
    public function register()
    {
        // $this->app->bind('tenant', function ($app) { // Commented out
        //     return $app->make(TenantMiddleware::class);
        // });
    }

    public function boot()
    {
        //
    }
} 