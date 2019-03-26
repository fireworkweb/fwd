<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class Ps extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'ps';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show fwd environment containers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $process->dockerCompose('ps', $this->getArgs());
    }
}
