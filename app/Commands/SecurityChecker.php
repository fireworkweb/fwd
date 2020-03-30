<?php

namespace App\Commands;

use App\Builder\Php;
use App\Commands\Traits\HasDynamicArgs;
use App\Builder\SecurityChecker as SecurityCheckerBuilder;

class SecurityChecker extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'security-checker';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run security-checker command in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            SecurityCheckerBuilder::makeWithDefaultArgs($this->getArgs())
        );
    }
}
