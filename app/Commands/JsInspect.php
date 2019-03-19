<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Process;
use LaravelZero\Framework\Commands\Command;

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
    public function handle(Process $process)
    {
        $process->dockerRun(env('FWD_IMAGE_NODE_QA'), 'jsinspect', $this->getArgs());
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return 'src/';
    }
}
