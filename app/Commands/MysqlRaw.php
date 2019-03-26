<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class MysqlRaw extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'mysql-raw';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run Mysql client inside the container without any database.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $process->dockerCompose(
            'exec -e MYSQL_PWD=' . env('DB_PASSWORD') . ' mysql mysql -u root',
            $this->getArgs()
        );
    }
}
