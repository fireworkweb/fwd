<?php

namespace App\Commands;

use App\Builder\Yarn;
use App\Builder\Mysql;
use App\Builder\Artisan;
use App\Builder\Escaped;
use App\Builder\Composer;
use App\Builder\RedisCli;
use App\Builder\DockerComposeExec;

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
     * @return mixed
     */
    public function handle()
    {
        if ($envFile = $this->argument('envFile')) {
            $this->environment->overloadEnv(
                $this->environment->getContextEnv($envFile)
            );
        }

        $commands = [
            [$this, 'composerInstall'],
            [$this, 'redisFlushAll'],
            [$this, 'mysqlDropDatabase'],
            [$this, 'mysqlCreateDatabase'],
            [$this, 'mysqlGrantDatabase'],
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

    protected function composerInstall()
    {
        return $this->runTask('Composer Install', function () {
            return $this->commandExecutor->runQuietly(new Composer('install'));
        });
    }

    protected function redisFlushAll()
    {
        return $this->runTask('Redis Flush All', function () {
            return $this->commandExecutor->runQuietly(new RedisCli('flushall'));
        });
    }

    protected function mysqlDropDatabase()
    {
        return $this->runTask('MySQL Drop Database', function () {
            return $this->commandExecutor->runQuietly(new Mysql(
                '-e',
                Escaped::make(sprintf('drop database if exists %s', env('DB_DATABASE')))
            ));
        });
    }

    protected function mysqlCreateDatabase()
    {
        return $this->runTask('MySQL Create Database', function () {
            return $this->commandExecutor->runQuietly(new Mysql(
                '-e',
                Escaped::make(sprintf('create database %s', env('DB_DATABASE')))
            ));
        });
    }

    protected function mysqlGrantDatabase()
    {
        return $this->runTask('MySQL Grant Privileges', function () {
            return $this->commandExecutor->runQuietly(new Mysql(
                '-e',
                Escaped::make(vsprintf('grant all on %s.* to %s@"%%"', [
                    env('DB_DATABASE'),
                    env('DB_USERNAME'),
                ]))
            ));
        });
    }

    protected function artisanMigrateFresh()
    {
        $task = $this->option('no-seed')
            ? 'Migrate Fresh'
            : 'Migrate Fresh Seed';

        return $this->runTask($task, function () {
            return $this->commandExecutor->runQuietly(
                tap(new Artisan('migrate:fresh'), function ($artisan) {
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

    protected function yarnInstall()
    {
        return $this->runTask('Yarn Install', function () {
            return $this->commandExecutor->runQuietly(new Yarn('install'));
        });
    }

    protected function yarnDev()
    {
        return $this->runTask('Yarn Dev', function () {
            return $this->commandExecutor->runQuietly(new Yarn('dev'));
        });
    }

    protected function clearCompiled()
    {
        return $this->runTask('Clear Compiled', function () {
            return $this->commandExecutor->runQuietly(new Artisan('clear-compiled'));
        });
    }

    protected function clearCache()
    {
        return $this->runTask('Clear Cache', function () {
            return $this->commandExecutor->runQuietly(new Artisan('cache:clear'));
        });
    }

    protected function clearConfig()
    {
        return $this->runTask('Clear Config', function () {
            return $this->commandExecutor->runQuietly(new Artisan('config:clear'));
        });
    }

    protected function clearRoute()
    {
        return $this->runTask('Clear Route', function () {
            return $this->commandExecutor->runQuietly(new Artisan('route:clear'));
        });
    }

    protected function clearView()
    {
        return $this->runTask('Clear View', function () {
            return $this->commandExecutor->runQuietly(new Artisan('view:clear'));
        });
    }

    protected function clearLogs()
    {
        return $this->runTask('Clear Logs', function () {
            return $this->commandExecutor->runQuietly(new DockerComposeExec(
                'app rm',
                '-f',
                Escaped::make($this->environment->getContextFile('storage/logs/*.log'))
            ));
        });
    }
}
