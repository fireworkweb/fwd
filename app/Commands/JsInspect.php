<?php

namespace App\Commands;

use App\Builder\NodeQa;
use App\CommandExecutor;
use App\Builder\Argument;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class JsInspect extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'jsinspect';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run jsinspect in the NODE-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CommandExecutor $executor)
    {
        return $executor->run(new NodeQa(
            Argument::raw('jsinspect'),
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
        return 'src/';
    }
}
