<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Commands\Traits\Process;

class Run extends Command
{
    use Process;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'run {service} {--command=}';

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
        $this->dockerCompose(
            'exec',
            $this->argument('service'),
            $this->option('command')
        );
    }
}
