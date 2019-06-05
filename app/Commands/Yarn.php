<?php

namespace App\Commands;

use App\CommandExecutor;
use App\Builder\Argument;
use App\Builder\Yarn as YarnBuilder;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class Yarn extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'yarn';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run yarn in a new container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CommandExecutor $executor)
    {
        return $executor->run(new YarnBuilder(Argument::raw($this->getArgs())));
    }
}
