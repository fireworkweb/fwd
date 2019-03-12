<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Commands\Traits\Process;
use LaravelZero\Framework\Commands\Command;

class Ps extends Command
{
    use HasDynamicArgs, Process;

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
    public function handle()
    {
        $this->dockerCompose('ps', $this->getArgs());
    }
}
