<?php

namespace App\Commands;

use App\Builder\Composer as ComposerBuilder;
use App\Commands\Traits\HasDynamicArgs;

class Composer extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'composer';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run composer within the Application container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            ComposerBuilder::makeWithDefaultArgs($this->getArgs())
        );
    }
}
