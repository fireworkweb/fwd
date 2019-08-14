<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Builder\PhpCsFixer as PhpCsFixerBuilder;

class PhpCsFixer extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'php-cs-fixer';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run php-cs-fixer in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            PhpCsFixerBuilder::make($this->getArgs())
        );
    }
}
