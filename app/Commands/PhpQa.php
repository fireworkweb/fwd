<?php

namespace App\Commands;

use App\Builder\PhpQa as PhpQaBuilder;
use App\Commands\Traits\HasDynamicArgs;

class PhpQa extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'php-qa';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run shell commands in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            PhpQaBuilder::make($this->getArgs())
        );
    }
}
