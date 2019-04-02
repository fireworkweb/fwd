<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
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
        if (empty($this->getArgs())) {
            $process->tty(true);
        }

        $process->dockerCompose(
            sprintf('exec -e MYSQL_PWD=%s mysql mysql -u root',  env('DB_PASSWORD')),
            env('DB_DATABASE'),
            $this->getArgs()
        );
    }
}
