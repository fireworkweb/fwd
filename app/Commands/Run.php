<?php

namespace App\Commands;

use App\Builder\DockerRun;
use App\Commands\Traits\HasDynamicArgs;

class Run extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'run';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run containers with fwd bindings.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            DockerRun::makeWithDefaultArgs($this->getArgs())
        );
    }
}
