<?php

namespace App\Commands;

use App\CommandExecutor;
use App\Commands\Traits\HasDynamicArgs;
use App\Builder\NodeQa as NodeQaBuilder;
use LaravelZero\Framework\Commands\Command;

class NodeQa extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'node-qa';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run shell commands in the NODE-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CommandExecutor $executor)
    {
        return $executor->run(new NodeQaBuilder($this->getArgs()));
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return 'node -v';
    }
}
