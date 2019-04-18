<?php

namespace App\Commands;

use App\Process;
use App\Environment;
use App\Commands\Traits\ArtisanCall;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class Reset extends Command
{
    use ArtisanCall;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'reset {envFile?} {--clear} {--clear-logs} {--no-seed}';

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

        $artisanMigrateFresh = $this->option('no-seed')
            ? 'artisanMigrateFresh'
            : 'artisanMigrateFreshSeed';

        $commands = [
            [$this, 'composerInstall'],
            [$this, 'mysqlDropDatabase'],
            [$this, 'mysqlCreateDatabase'],
            [$this, 'mysqlGrantDatabase'],
            [$this, $artisanMigrateFresh],
            [$this, 'yarnInstall'],
            [$this, 'yarnDev'],
        ];

        if ($this->option('clear')) {
            $commands[] = [$this, 'clearCompiled'];
            $commands[] = [$this, 'cacheClear'];
            $commands[] = [$this, 'configClear'];
            $commands[] = [$this, 'routeClear'];
            $commands[] = [$this, 'viewClear'];
        }

        if ($this->option('clear-logs')) {
            $commands[] = [$this, 'clearLogs'];
        }

        // Run commands, first that isn't success (0) stops and return that exitCode
        foreach ($commands as $command) {
            if ($exitCode = call_user_func($command, $environment, $process)) {
                return $exitCode;
            }
        }

        return 0;
    }

    protected function composerInstall()
    {
        return $this->artisanCall('composer', ['install']);
    }

    protected function mysqlDropDatabase()
    {
        return $this->artisanCall('mysql-raw', [
            '-e',
            sprintf('drop database if exists %s', env('DB_DATABASE')),
        ]);
    }

    protected function mysqlCreateDatabase()
    {
        return $this->artisanCall('mysql-raw', [
            '-e',
            sprintf('create database %s', env('DB_DATABASE')),
        ]);
    }

    protected function mysqlGrantDatabase()
    {
        return $this->artisanCall('mysql-raw', ['-e', sprintf(
            'grant all on %s.* to %s@"%%"',
            env('DB_DATABASE'),
            env('DB_USERNAME')
        )]);
    }

    protected function artisanMigrateFresh(Environment $environment, Process $process)
    {
        return $process->dockerCompose(
            'exec',
            sprintf('-e DB_DATABASE=%s', env('DB_DATABASE')),
            sprintf('-e DB_USERNAME=%s', env('DB_USERNAME')),
            sprintf('-e DB_PASSWORD=%s', env('DB_PASSWORD')),
            'app php artisan migrate:fresh'
        );
    }

    protected function artisanMigrateFreshSeed(Environment $environment, Process $process)
    {
        return $process->dockerCompose(
            'exec',
            sprintf('-e DB_DATABASE=%s', env('DB_DATABASE')),
            sprintf('-e DB_USERNAME=%s', env('DB_USERNAME')),
            sprintf('-e DB_PASSWORD=%s', env('DB_PASSWORD')),
            'app php artisan migrate:fresh --seed'
        );
    }

    protected function yarnInstall()
    {
        return $this->artisanCall('yarn', ['install']);
    }

    protected function yarnDev()
    {
        return $this->artisanCall('yarn', ['dev']);
    }

    protected function clearCompiled()
    {
        return $this->artisanCall('artisan', ['clear-compiled']);
    }

    protected function clearCache()
    {
        return $this->artisanCall('artisan', ['cache:clear']);
    }

    protected function clearConfig()
    {
        return $this->artisanCall('artisan', ['config:clear']);
    }

    protected function clearRoute()
    {
        return $this->artisanCall('artisan', ['route:clear']);
    }

    protected function clearView()
    {
        return $this->artisanCall('artisan', ['view:clear']);
    }

    protected function clearLogs(Environment $environment, Process $process)
    {
        collect(File::glob($environment->getContextFile('storage/logs/*.log')))
            ->each(function ($file) {
                File::delete($file);
            });

        return 0;
    }
}
