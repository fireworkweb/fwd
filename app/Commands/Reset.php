<?php

namespace App\Commands;

use App\Builder\Artisan;
use App\Builder\Composer;
use App\Builder\DockerComposeExec;
use App\Builder\Escaped;
use App\Builder\Mysql;
use App\Builder\RedisCli;
use App\Builder\Yarn;

class Reset extends Command
{
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
     * @return int
     */
    public function handle() : int
    {
        if ($envFile = $this->argument('envFile')) {
            $this->environment->overloadEnv(
                $this->environment->getContextEnv($envFile)
            );
        }

        $commands = [
            [$this, 'composerInstall'],
            [$this, 'cacheFlushAll'],
            [$this, 'datatabaseDropDatabase'],
            [$this, 'databaseCreateDatabase'],
            [$this, 'databaseGrantDatabase'],
            [$this, 'artisanMigrateFresh'],
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

        return $this->runCommands($commands);
    }

    protected function composerInstall() : int
    {
        return $this->runTask('Composer Install', function () {
            return $this->commandExecutor->runQuietly(Composer::make('install'));
        });
    }

    protected function cacheFlushAll() : int
    {
        return $this->runTask('Cache Flush All', function () {
            return $this->commandExecutor->runQuietly(RedisCli::make('flushall'));
        });
    }

    protected function datatabaseDropDatabase() : int
    {
        return $this->runTask('MySQL Drop Database', function () {
            return $this->commandExecutor->runQuietly(Mysql::make(
                '-e',
                Escaped::make(sprintf('drop database if exists %s', env('DB_DATABASE')))
            ));
        });
    }

    protected function databaseCreateDatabase() : int
    {
        return $this->runTask('MySQL Create Database', function () {
            return $this->commandExecutor->runQuietly(Mysql::make(
                '-e',
                Escaped::make(sprintf('create database %s', env('DB_DATABASE')))
            ));
        });
    }

    protected function databaseGrantDatabase() : int
    {
        return $this->runTask('MySQL Grant Privileges', function () {
            return $this->commandExecutor->runQuietly(Mysql::make(
                '-e',
                Escaped::make(vsprintf('grant all on %s.* to %s@"%%"', [
                    env('DB_DATABASE'),
                    env('DB_USERNAME'),
                ]))
            ));
        });
    }

    protected function artisanMigrateFresh() : int
    {
        $task = $this->option('no-seed')
            ? 'Migrate Fresh'
            : 'Migrate Fresh Seed';

        return $this->runTask($task, function () {
            return $this->commandExecutor->runQuietly(
                tap(Artisan::make('migrate:fresh'), function (Artisan $artisan) {
                    if (! $this->option('no-seed')) {
                        $artisan->addArgument('--seed');
                    }

                    $artisan->getPhp()->getDockerComposeExec()->addEnvs([
                        'DB_DATABASE' => env('DB_DATABASE'),
                        'DB_USERNAME' => env('DB_USERNAME'),
                        'DB_PASSWORD' => env('DB_PASSWORD'),
                    ]);
                })
            );
        });
    }

    protected function yarnInstall() : int
    {
        return $this->runTask('Yarn Install', function () {
            return $this->commandExecutor->runQuietly(Yarn::make('install'));
        });
    }

    protected function yarnDev(): int
    {
        return $this->runTask('Yarn Dev', function () {
            return $this->commandExecutor->runQuietly(Yarn::make('dev'));
        });
    }

    protected function clearCompiled() : int
    {
        return $this->runTask('Clear Compiled', function () {
            return $this->commandExecutor->runQuietly(Artisan::make('clear-compiled'));
        });
    }

    protected function clearCache() : int
    {
        return $this->runTask('Clear Cache', function () {
            return $this->commandExecutor->runQuietly(Artisan::make('cache:clear'));
        });
    }

    protected function clearConfig() : int
    {
        return $this->runTask('Clear Config', function () {
            return $this->commandExecutor->runQuietly(Artisan::make('config:clear'));
        });
    }

    protected function clearRoute() : int
    {
        return $this->runTask('Clear Route', function () {
            return $this->commandExecutor->runQuietly(Artisan::make('route:clear'));
        });
    }

    protected function clearView() : int
    {
        return $this->runTask('Clear View', function () {
            return $this->commandExecutor->runQuietly(Artisan::make('view:clear'));
        });
    }

    protected function clearLogs() : int
    {
        return $this->runTask('Clear Logs', function () {
            return $this->commandExecutor->runQuietly(DockerComposeExec::make(
                'app rm',
                '-f',
                Escaped::make($this->environment->getContextFile('storage/logs/*.log'))
            ));
        });
    }
}
