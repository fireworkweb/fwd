<?php

namespace App\Commands;

use App\Builder\Phan as PhanBuilder;
use App\Commands\Traits\HasDynamicArgs;

class Phan extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'phan';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run phan in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            PhanBuilder::make($this->getArgs())
        );
    }
}
