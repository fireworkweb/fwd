<?php

namespace App\Commands;

use App\CommandExecutor;
use App\Builder\Unescaped;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;
use App\Builder\Composer as ComposerCommand;

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
    public function handle(CommandExecutor $executor)
    {
        return $executor->run(new ComposerCommand(Unescaped::make($this->getArgs())));
    }
}
