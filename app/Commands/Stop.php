<?php

namespace App\Commands;

use App\Builder\DockerCompose;
use App\Commands\Traits\HasDynamicArgs;

class Stop extends Command
{
    use HasDynamicArgs;

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
    protected $description = 'Stop fwd environment containers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            DockerCompose::makeWithDefaultArgs('stop', $this->getArgs())
        );
    }
}
