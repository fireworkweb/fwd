<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Builder\Mysql as MysqlBuilder;

class Mysql extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'mysql';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run Mysql client inside the container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            MysqlBuilder::make(env('DB_DATABASE'), $this->getArgs())
        );
    }
}
