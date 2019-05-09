<?php

namespace App\Commands;

use App\Process;
use App\Environment;
use App\Commands\Traits\RunTask;
use App\Commands\Traits\ArtisanCall;
use LaravelZero\Framework\Commands\Command;

class Reset extends Command
{
    use ArtisanCall, RunTask;

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
            $commands[] = [$this, 'clearCache'];
            $commands[] = [$this, 'clearConfig'];
            $commands[] = [$this, 'clearRoute'];
            $commands[] = [$this, 'clearView'];
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
        return $this->runTask('Composer Install', function() {
            return $this->artisanCallNoOutput('composer', ['install']);
        });
    }

    protected function mysqlDropDatabase()
    {
        return $this->runTask('MySQL Drop Database', function() {
            return $this->artisanCallNoOutput('mysql-raw', [
                '-e',
                sprintf('drop database if exists %s', env('DB_DATABASE')),
            ]);
        });
    }

    protected function mysqlCreateDatabase()
    {
        return $this->runTask('MySQL Create Database', function() {
            return $this->artisanCallNoOutput('mysql-raw', [
                '-e',
                sprintf('create database %s', env('DB_DATABASE')),
            ]);
        });
    }

    protected function mysqlGrantDatabase()
    {
        return $this->runTask('MySQL Grant Privileges', function() {
            return $this->artisanCallNoOutput('mysql-raw', ['-e', sprintf(
                'grant all on %s.* to %s@"%%"',
                env('DB_DATABASE'),
                env('DB_USERNAME')
            )]);
        });
    }

    protected function artisanMigrateFresh(Environment $environment, Process $process)
    {
        return $this->runTask('Migrate Fresh', function() use ($process) {
            return $process->dockerComposeExec(
                sprintf('-e DB_DATABASE=%s', env('DB_DATABASE')),
                sprintf('-e DB_USERNAME=%s', env('DB_USERNAME')),
                sprintf('-e DB_PASSWORD=%s', env('DB_PASSWORD')),
                'app php artisan migrate:fresh'
            );
        });
    }

    protected function artisanMigrateFreshSeed(Environment $environment, Process $process)
    {
        return $this->runTask('Migrate Fresh Seed', function() use ($process) {
            return $process->dockerComposeExecNoOutput(
                sprintf('-e DB_DATABASE=%s', env('DB_DATABASE')),
                sprintf('-e DB_USERNAME=%s', env('DB_USERNAME')),
                sprintf('-e DB_PASSWORD=%s', env('DB_PASSWORD')),
                'app php artisan migrate:fresh --seed'
            );
        });
    }

    protected function yarnInstall()
    {
        return $this->runTask('Yarn Install', function() {
            return $this->artisanCallNoOutput('yarn', ['install']);
        });
    }

    protected function yarnDev()
    {
        return $this->runTask('Yarn Dev', function() {
            return $this->artisanCallNoOutput('yarn', ['dev']);
        });
    }

    protected function clearCompiled()
    {
        return $this->runTask('Clear Compiled', function() {
            return $this->artisanCallNoOutput('artisan', ['clear-compiled']);
        });
    }

    protected function clearCache()
    {
        return $this->runTask('Clear Cache', function() {
            return $this->artisanCallNoOutput('artisan', ['cache:clear']);
        });
    }

    protected function clearConfig()
    {
        return $this->runTask('Clear Config', function() {
            return $this->artisanCallNoOutput('artisan', ['config:clear']);
        });
    }

    protected function clearRoute()
    {
        return $this->runTask('Clear Route', function() {
            return $this->artisanCallNoOutput('artisan', ['route:clear']);
        });
    }

    protected function clearView()
    {
        return $this->runTask('Clear View', function() {
            return $this->artisanCallNoOutput('artisan', ['view:clear']);
        });
    }

    protected function clearLogs(Environment $environment, Process $process)
    {
        return $this->runTask('Clear Logs', function() use ($environment, $process) {
            return $process->dockerComposeExecNoOutput(
                'app rm -f',
                $environment->getContextFile('storage/logs/*.log')
            );
        });
    }
}
