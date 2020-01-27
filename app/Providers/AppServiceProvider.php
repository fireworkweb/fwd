<?php

namespace App\Providers;

use App\Checker;
use App\CommandExecutor;
use App\Environment;
use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Checker::class);
        $this->app->singleton(Environment::class);
        $this->app->singleton(CommandExecutor::class);

        $this->loadFwd();
    }

    protected function loadFwd()
    {
        app(Environment::class)->load();

        if ($commands = $this->getCommands()) {
            $this->commands($commands);
        }
    }

    protected function getCommands()
    {
        if (! is_dir(env('FWD_CUSTOM_PATH'))) {
            return;
        }

        return collect((new Finder())->in(env('FWD_CUSTOM_PATH'))->files())
            ->map(function (SplFileInfo $file) {
                return $file->getPathname();
            })
            ->each(function ($path) {
                require_once $path;
            })
            ->map(function ($path) {
                return pathinfo($path, PATHINFO_FILENAME);
            })
            ->filter(function ($file) {
                return is_subclass_of($file, Command::class);
            })
            ->values()
            ->toArray();
    }
}
