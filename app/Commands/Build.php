<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class Build extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'build';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run docker-compose build.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $process->dockerCompose('build', $this->getArgs());
    }
}
