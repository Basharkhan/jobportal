<?php

namespace App\Providers;

use App\Repositories\AdminRepository;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\EmployerRepository;
use App\Repositories\Interfaces\EmployerRepositoryInterface;
use App\Repositories\Interfaces\UserAuthRepositoryInterface;
use App\Repositories\UserAuthRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
    * Register any application services.
    */

    public function register(): void {
        $this->app->bind( UserAuthRepositoryInterface::class, UserAuthRepository::class );        
    }

    /**
    * Bootstrap any application services.
    */

    public function boot(): void {

    }
}
