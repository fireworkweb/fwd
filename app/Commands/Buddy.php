<?php

namespace App\Commands;

use App\Builder\Buddy as BuddyBuilder;
use App\Commands\Traits\HasDynamicArgs;

class Buddy extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'buddy';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run buddy in the NODE-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            BuddyBuilder::make($this->getArgs())
        );
    }
}
