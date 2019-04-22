<?php

namespace App\Commands;

use App\Process;
use LaravelZero\Framework\Commands\Command;

class Ssh extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ssh {service} {--shell=bash}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start a bash session on a specific service (app, http, mysql)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        return $process->dockerCompose(
            'exec',
            $this->argument('service'),
            $this->option('shell')
        );
    }
}
