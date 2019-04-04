<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class Yarn extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'yarn';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run yarn in a new container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $process->dockerRun(
            env('FWD_IMAGE_NODE'),
            'yarn',
            $this->getArgs()
        );
    }
}
