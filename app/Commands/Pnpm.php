<?php

namespace App\Commands;

use App\Builder\Pnpm as PnpmBuilder;
use App\Commands\Traits\HasDynamicArgs;

class Pnpm extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'pnpm';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run pnpm in a new container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            PnpmBuilder::makeWithDefaultArgs($this->getArgs())
        );
    }
}
