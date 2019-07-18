<?php

namespace App\Commands;

use App\Builder\PhpQa;
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
            PhpQa::make('phan', $this->getArgs())
        );
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return '--color -p -l app -iy 5';
    }
}
