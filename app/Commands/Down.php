<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Process;
use LaravelZero\Framework\Commands\Command;

class Down extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'down';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get down all containers and destroy them.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $process->dockerCompose('down', $this->getArgs());
    }
}
