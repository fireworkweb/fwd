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
                            {--no-checks : Do not wait for Database to become available}
                            {--timeout=60 : The number of seconds to wait}
                            {--all : Start all services}
                            {--services= : The services from docker-compose.yml to be started}';

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
        return StartTask::make($this)
            ->checks(! $this->option('no-checks'))
            ->timeout($this->option('timeout'))
            ->services((string) $this->option('services'))
            ->all($this->option('all'))
            ->run();
    }
}
