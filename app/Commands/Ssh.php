<?php

namespace App\Commands;

use App\Builder\DockerCompose;

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
    protected $description = 'Start a shell CLI session on a specific service (app, http, database)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            DockerCompose::makeWithDefaultArgs(
                'exec',
                $this->argument('service'),
                $this->option('shell')
            )
        );
    }
}
