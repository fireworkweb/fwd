<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Environment;
use App\Process;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Environment::load();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Process::class);
    }
}
