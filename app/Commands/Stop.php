<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Commands\Traits\Process;
use LaravelZero\Framework\Commands\Command;

class Stop extends Command
{
    use Process, HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'stop';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Stop containers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dockerCompose('stop', $this->args);
    }
}
