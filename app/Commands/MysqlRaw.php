<?php

namespace App\Commands;

use App\Builder\Mysql;
use App\Commands\Traits\HasDynamicArgs;

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
    public function handle()
    {
        return $this->commandExecutor->run(
            Mysql::makeWithDefaultArgs($this->getArgs())
        );
    }
}
