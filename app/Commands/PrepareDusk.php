<?php

namespace App\Commands;

use App\Commands\Traits\ArtisanCall;
use App\Environment;
use App\Process;
use LaravelZero\Framework\Commands\Command;

class PrepareDusk extends Command
{
    use ArtisanCall;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'prepare-dusk';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a test dedicated database named dusk.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Environment $environment, Process $process)
    {
        $environment->loadEnv($environment->getContextEnv('.env.dusk.local'), true);

        $this->artisanCall('mysql-raw', ['-e', sprintf('drop database if exists %s', env('DB_DATABASE'))]);
        $this->artisanCall('mysql-raw', ['-e', sprintf('create database %s', env('DB_DATABASE'))]);
        $this->artisanCall('mysql-raw', ['-e', sprintf('grant all on %s.* to %s@"%%"', env('DB_DATABASE'), env('DB_USERNAME'))]);

        $process->dockerCompose(
            'exec',
            sprintf('-e DB_DATABASE=%s', env('DB_DATABASE')),
            sprintf('-e DB_USERNAME=%s', env('DB_USERNAME')),
            sprintf('-e DB_PASSWORD=%s', env('DB_PASSWORD')),
            'app php artisan migrate:fresh --seed',
        );
    }
}
