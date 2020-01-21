<?php

namespace App\Commands;

use App\Builder\DockerCompose;
use App\Commands\Traits\HasDynamicArgs;

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
    public function handle()
    {
        $this->commandExecutor->run(
            DockerCompose::makeWithDefaultArgs('build', $this->getArgs())
        );
    }
}
