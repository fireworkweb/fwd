<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Process;
use LaravelZero\Framework\Commands\Command;

class Up extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'up';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start fwd environment containers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $process->dockerCompose('up', $this->getArgs());
    }
}
