<?php

namespace App\Commands;

use App\Builder\DockerCompose;
use App\Commands\Traits\HasDynamicArgs;

class Down extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'down';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get down all containers and destroy them.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            DockerCompose::make('down', $this->getArgs())
        );
    }
}
