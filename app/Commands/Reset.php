<?php

namespace App\Commands;

use App\Process;
use App\Environment;
use App\Commands\Traits\ArtisanCall;
use LaravelZero\Framework\Commands\Command;

class Reset extends Command
{
    use ArtisanCall;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'reset {envFile=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Reset environment.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Environment $environment, Process $process)
    {
        if ($envFile = $this->argument('envFile')) {
            $environment->overloadEnv($environment->getContextEnv($envFile));
        }

        return collect([
            function () use ($process) {
                return $process->dockerComposeExec('app composer install');
            },
            function () {
                return $this->artisanCall('mysql-raw', [
                    '-e',
                    sprintf('drop database if exists %s', env('DB_DATABASE')),
                ]);
            },
            function () {
                return $this->artisanCall('mysql-raw', [
                    '-e',
                    sprintf('create database %s', env('DB_DATABASE')),
                ]);
            },
            function () {
                return $this->artisanCall('mysql-raw', ['-e', sprintf(
                    'grant all on %s.* to %s@"%%"',
                    env('DB_DATABASE'),
                    env('DB_USERNAME')
                )]);
            },
            function () use ($process) {
                return $process->dockerCompose(
                    'exec',
                    sprintf('-e DB_DATABASE=%s', env('DB_DATABASE')),
                    sprintf('-e DB_USERNAME=%s', env('DB_USERNAME')),
                    sprintf('-e DB_PASSWORD=%s', env('DB_PASSWORD')),
                    'app php artisan migrate:fresh --seed'
                );
            },
            function () use ($process) {
                return $process->dockerRun(env('FWD_IMAGE_NODE'), 'yarn install');
            },
            function () use ($process) {
                return $process->dockerRun(env('FWD_IMAGE_NODE'), 'yarn dev');
            },
        ])->first(function ($command) {
            return $command();
        }, 0);
    }
}
