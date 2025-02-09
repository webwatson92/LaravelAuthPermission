<?php

namespace Webwatson92\LaravelAuth;

use Webwatson92\LaravelAuth\Console\InstalllaravelauthCommand;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            InstalllaravelauthCommand::class,
        ]);
        
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        
        $this->loadRoutesFrom(__DIR__.'/../routes/userRolePermission.php');
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravelauth');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravelauth'),
        ], 'laravelauth');
        
        $this->publishes([
            __DIR__.'/database/seeders' => database_path('seeders'),
        ], 'laravelauth');
    }
}
