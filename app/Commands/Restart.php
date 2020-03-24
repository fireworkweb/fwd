<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Tasks\Start as StartTask;
use App\Tasks\Stop as StopTask;

class Restart extends Command
{
    use HasDynamicArgs;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'restart
                            {--purge : Removes all data persisted from containers by removing the underlying Docker volumes}
                            {--no-checks : Do not wait for Database to become available}
                            {--timeout=60 : The number of seconds to wait}
                            {--all : Start all services}
                            {--services= : The services from docker-compose.yml to be started}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Runs stop and start commands at once.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stopTask = StopTask::make($this)
            ->purge((bool) $this->option('purge'))
            ->run();

        if ($stopTask) {
            return $stopTask;
        }

        return StartTask::make($this)
            ->checks(! $this->option('no-checks'))
            ->timeout($this->option('timeout'))
            ->services((string) $this->option('services'))
            ->all($this->option('all'))
            ->run();
    }
}
