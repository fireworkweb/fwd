<?php

namespace App\Commands;

use App\Builder\DockerCompose;

class Stop extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'stop {--purge}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get down all containers AND DESTROY THEM.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $args = ['down'];

        if ($this->option('purge')) {
            $args[] = '-v';
        }

        return $this->commandExecutor->run(
            DockerCompose::makeWithDefaultArgs(...$args)
        );
    }
}
