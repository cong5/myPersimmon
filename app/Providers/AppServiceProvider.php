<?php

namespace App\Providers;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Validator::extend('flag', 'Persimmon\Validator\MyValidator@validateFlag');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
