<?php

namespace App\Commands;

use App\CommandExecutor;
use App\Builder\Argument;
use App\Builder\Node as NodeBuilder;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

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
    public function handle(CommandExecutor $executor)
    {
        return $executor->run(new NodeBuilder(
            Argument::raw($this->getArgs())
        ));
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return '-v';
    }
}
