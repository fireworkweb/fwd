<?php

namespace App\Commands;

use App\Builder\Php as PhpBuilder;
use App\Commands\Traits\HasDynamicArgs;

class Php extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'php';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run shell commands in the APP container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            PhpBuilder::make($this->getArgs())
        );
    }
}
