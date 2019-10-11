<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class MySqlDump extends Command
{
    use HasDynamicArgs;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'mysql-dump';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run MysqlDump client inside the container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        return $process->dockerCompose(
            sprintf('exec -e MYSQL_PWD=%s mysql mysqldump -u root', env('DB_PASSWORD')),
            env('DB_DATABASE')
        );
    }
}
