<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Process;
use LaravelZero\Framework\Commands\Command;

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
    public function handle(Process $process)
    {
        $process->dockerCompose('exec -e MYSQL_PWD='.env('DB_PASSWORD').' mysql mysql -u root '.env('DB_DATABASE'), $this->getArgs());
    }
}
