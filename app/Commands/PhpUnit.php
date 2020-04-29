<?php

namespace App\Commands;

use App\Builder\Php;
use App\Commands\Traits\HasDynamicArgs;

class PhpUnit extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'phpunit';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run phpunit commands in the APP container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            Php::makeWithDefaultArgs('vendor/bin/phpunit', $this->getArgs())
        );
    }
}
