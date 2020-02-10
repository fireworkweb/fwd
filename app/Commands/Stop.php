<?php

namespace App\Commands;

use App\Tasks\Stop as TasksStop;

class Stop extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'stop
                                {--all : Stop all services}
                                {--services= : The services from docker-compose.yml to be stopped}
                                {--purge : Removes all data persisted from containers by removing the underlying Docker volumes}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get down containers AND DESTROY THEM.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $task = TasksStop::make($this)->purge((bool) $this->option('purge'));

        if (! $this->option('all')) {
            $task->services((string) $this->option('services'));
        }

        return $task->run();
    }
}
