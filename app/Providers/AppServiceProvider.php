<?php

namespace App\Providers;

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
        $this->processEnv();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function processEnv()
    {
        // @TODO: process project env

        $this->processEnvPath();
    }

    protected function processEnvPath()
    {
        $name = 'FWD_SSH_KEY_PATH';
        $value = str_replace('$HOME', $_SERVER['HOME'], env($name));

        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}
