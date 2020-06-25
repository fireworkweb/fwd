<?php

namespace App\Commands;

use App\Builder\Escaped;
use App\Builder\Mysql;

class ResetDb extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'reset-db {envFile?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Reset DB.';

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
            [$this, 'databaseDropDatabase'],
            [$this, 'databaseCreateDatabase'],
            [$this, 'databaseGrantDatabase'],
        ];

        return $this->runCommands($commands);
    }

    protected function databaseDropDatabase() : int
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
}
