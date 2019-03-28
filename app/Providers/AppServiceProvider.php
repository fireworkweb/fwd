<?php

namespace App\Providers;

use App\Process;
use App\Environment;
use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
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
        //
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

        $this->loadFwd();
    }

    protected function loadFwd()
    {
        app(Environment::class)->load();

        $this->commands($this->getCommands());
    }

    protected function getCommands()
    {
        return collect((new Finder)->in(env('FWD_CUSTOM_PATH'))->files())
            ->map(function ($command) {
                return $command->getPathname();
            })
            ->each(function ($command) {
                require_once($command);
            })
            ->map(function ($command) {
                return pathinfo($command, PATHINFO_FILENAME);
            })
            ->filter(function ($command) {
                return is_subclass_of($command, Command::class);
            })
            ->values()
            ->toArray();
    }
}
