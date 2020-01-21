<?php

namespace App\Commands;

use App\Builder\Builder;
use App\Commands\Traits\HasDynamicArgs;

class Ls extends Command
{
    use HasDynamicArgs;

    protected $name = 'ls';
    protected $description = 'ls example';

    public function handle()
    {
        if ($this->ask('Run?') === 'yes') {
            return $this->commandExecutor->run(Builder::make('ls', $this->getArgs()));
        }
    }
}
