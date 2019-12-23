<?php

namespace App\Commands;

use App\Builder\DockerRun as DockerRunBuilder;
use App\Commands\Traits\HasDynamicArgs;

class DockerRun extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'docker-run';

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
            DockerRunBuilder::makeWithDefaultArgs($this->getArgs())
        );
    }
}
