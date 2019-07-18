<?php

namespace App\Commands;

use App\Builder\DockerCompose;
use App\Commands\Traits\HasDynamicArgs;

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
    public function handle()
    {
        return $this->commandExecutor->run(
            DockerCompose::make('up', $this->getArgs())
        );
    }
}
