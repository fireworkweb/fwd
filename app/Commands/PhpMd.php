<?php

namespace App\Commands;

use App\Builder\PhpMd as PhpMdBuilder;
use App\Commands\Traits\HasDynamicArgs;

class PhpMd extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'phpmd';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run phpmd in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            PhpMdBuilder::make($this->getArgs())
        );
    }
}
