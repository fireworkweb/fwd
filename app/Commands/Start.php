<?php

namespace App\Commands;

use App\Builder\Builder;
use App\Builder\Escaped;
use App\Tasks\Start as StartTask;
use Illuminate\Support\Facades\File;

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
        $noPortBinding = $this->option('no-port-binding');
        $timeout = ! $this->option('no-wait') ? $this->option('timeout') : 0;

        if ($noPortBinding) {
            File::copy(
                $this->environment->getContextDockerCompose(),
                $this->environment->getContextDockerCompose() . '.bak'
            );

            $this->commandExecutor->runQuietly(Builder::make(
                'sed',
                '-i',
                Escaped::make("/ports:/d"),
                'docker-compose.yml'
            ));
        }

        $task = StartTask::make($this)->timeout($timeout);

        if (! $this->option('all')) {
            $task->services((string) $this->option('services'));
        }

        try {
            return $task->run();
        } finally {
            if ($noPortBinding) {
                File::copy(
                    $this->environment->getContextDockerCompose() . '.bak',
                    $this->environment->getContextDockerCompose()
                );

                File::delete($this->environment->getContextDockerCompose() . '.bak');
            }
        }
    }
}
