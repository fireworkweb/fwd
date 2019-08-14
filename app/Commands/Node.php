<?php

namespace App\Commands;

use App\Builder\Node as NodeBuilder;
use App\Commands\Traits\HasDynamicArgs;

class Node extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'node';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run node commands within a new container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            NodeBuilder::make($this->getArgs())
        );
    }
}
