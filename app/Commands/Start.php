<?php

namespace App\Commands;

use App\Tasks\Start as StartTask;

class Start extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'start
                            {--all : Start all services}
                            {--services= : The services from docker-compose.yml to be started}
                            {--no-port-binding : Skip port binding}
                            {--no-wait : Do not wait for Docker and MySQL to become available}
                            {--timeout=60 : The number of seconds to wait}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start fwd environment containers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timeout = ! $this->option('no-wait') ? $this->option('timeout') : 0;

        $task = StartTask::make($this)
            ->timeout($timeout)
            ->noPortBinding($this->option('no-port-binding'));

        if (! $this->option('all')) {
            $task->services((string) $this->option('services'));
        }

        return $task->run();
    }
}
