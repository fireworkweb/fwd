<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Builder\PhpCpd as PhpCpdBuilder;

class PhpCpd extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'phpcpd';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run phpcpd in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            PhpCpdBuilder::makeWithDefaultArgs($this->getArgs())
        );
    }
}
