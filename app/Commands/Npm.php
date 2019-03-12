<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Commands\Traits\Process;
use LaravelZero\Framework\Commands\Command;

class Npm extends Command
{
    use Process, HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'npm';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run npm in a new container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dockerRun(
            env('FWD_IMAGE_NODE'),
            'npm',
            $this->args
        );
    }
}
