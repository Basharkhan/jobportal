<?php

namespace App\Providers;

use App\Repositories\EmployerRepository;
use App\Repositories\Interfaces\EmployerRepositoryInterface;
use App\Repositories\Interfaces\JobPostingRepositoryInterface;
use App\Repositories\Interfaces\UserAuthRepositoryInterface;
use App\Repositories\JobPostingRepository;
use App\Repositories\UserAuthRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
    * Register any application services.
    */

    public function register(): void {
        $this->app->bind( UserAuthRepositoryInterface::class, UserAuthRepository::class );        
        $this->app->bind( JobPostingRepositoryInterface::class, JobPostingRepository::class );           
        $this->app->bind( EmployerRepositoryInterface::class, EmployerRepository::class );   
    }

    /**
    * Bootstrap any application services.
    */

    public function boot(): void {

    }
}
