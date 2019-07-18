<?php

namespace App\Commands;

use App\Builder\DockerCompose;
use App\Commands\Traits\HasDynamicArgs;

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
    public function handle()
    {
        return $this->commandExecutor->run(
            DockerCompose::make('ps', $this->getArgs())
        );
    }
}
