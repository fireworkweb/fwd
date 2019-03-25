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
        app(Environment::class)->load();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Environment::class);
        $this->app->singleton(Process::class);
    }
}
