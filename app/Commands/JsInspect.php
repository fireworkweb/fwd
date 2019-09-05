<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Builder\JsInspect as JsInspectBuilder;

class JsInspect extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'jsinspect';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run jsinspect in the NODE-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            JsInspectBuilder::makeWithDefaultArgs($this->getArgs())
        );
    }
}
