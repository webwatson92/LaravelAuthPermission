<?php 

namespace Webwatson92\LaravelAuth\Console;

use Illuminate\Console\Command;

class InstallAuthPackageCommand extends Command
{
    protected $signature = 'laravelauth:install';
    protected $description = 'Installe Laravel Breeze et configure Laravel Permission';

    public function handle()
    {
        $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
        $this->info('+++++++++++++++++++Installation de Laravel Breeze+++++++++++++++');
        $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');

        exec('composer require laravel/breeze --dev');
        $this->call('breeze:install', ['blade']);

        
        $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
        $this->info('+++++++++++++++Installation de Laravel Permission+++++++++++++++');
        $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');

        exec('composer require spatie/laravel-permission');
        $this->call('artisan', ['vendor:publish', '--provider' => 'Spatie\Permission\PermissionServiceProvider']);
        $this->call('artisan', ['migrate']);

        $this->info('Installation terminée ✅🏆FPM DEV TEAM => SDIS🏆');
    }
}
