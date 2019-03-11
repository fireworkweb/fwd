<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Commands\Traits\Process;

class Npm extends Command
{
    use Process;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'npm {--command=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run command in service.';

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
            $this->option('command')
        );
    }
}
