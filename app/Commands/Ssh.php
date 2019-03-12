<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Commands\Traits\Process;

class Ssh extends Command
{
    use Process;

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
    public function handle()
    {
        $this->dockerCompose(
            'exec',
            $this->argument('service'),
            $this->option('shell')
        );
    }
}
