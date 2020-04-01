<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Builder\PhpSecurityChecker as PhpSecurityCheckerBuilder;

class PhpSecurityChecker extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'php-security-checker';

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
            PhpSecurityCheckerBuilder::makeWithDefaultArgs($this->getArgs())
        );
    }
}
