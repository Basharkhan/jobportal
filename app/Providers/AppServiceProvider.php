<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
    * Register any application services.
    */

    public function register(): void {

    }

    /**
    * Bootstrap any application services.
    */

    public function boot(): void {
        //
    }
}
