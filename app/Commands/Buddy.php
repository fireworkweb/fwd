<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

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
    public function handle(Process $process)
    {
        $process->dockerRun(env('FWD_IMAGE_NODE_QA'), 'buddy', $this->getArgs());
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
