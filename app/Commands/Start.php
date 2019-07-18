<?php

namespace App\Commands;

use App\Checker;
use App\Builder\Mysql;
use App\Builder\DockerCompose;

class Start extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'start
                            {--no-wait : Do not wait for Docker and MySQL to become available}
                            {--timeout=60 : The number of seconds to wait}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start fwd environment containers.';

    protected $seconds = 0;

    protected $checker;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Checker $checker)
    {
        $this->checker = $checker;

        return $this->runCommands([
            [$this, 'checkDependencies'],
            [$this, 'dockerComposeUpD'],
            [$this, 'mysql'],
        ]);
    }

    protected function checkDependencies()
    {
        return $this->runTask('Checking dependencies', function () {
            if (! $this->checker->checkDocker()) {
                $this->error(sprintf(
                    'Incompatible docker version (Current: %s Required: %s).',
                    $this->checker->dockerVersion(),
                    Checker::DOCKER_MIN_VERSION
                ));

                return 1;
            }

            if (! $this->checker->checkDockerApi()) {
                $this->error(sprintf(
                    'Incompatible docker api version (Current: %s Required: %s).',
                    $this->checker->dockerApiVersion(),
                    Checker::DOCKER_API_MIN_VERSION
                ));

                return 1;
            }

            if (! $this->checker->checkDockerCompose()) {
                $this->error(sprintf(
                    'Incompatible docker-compose version (Current: %s Required: %s).',
                    $this->checker->dockerComposeVersion(),
                    Checker::DOCKER_COMPOSE_MIN_VERSION
                ));

                return 1;
            }

            return $this->runCommand(function () {
                return $this->commandExecutor->runQuietly(
                    DockerCompose::make('ps')
                );
            });
        });
    }

    protected function dockerComposeUpD()
    {
        return $this->runTask('Starting fwd', function () {
            return $this->commandExecutor->runQuietly(
                DockerCompose::make('up', '-d')
            );
        });
    }

    protected function mysql()
    {
        return $this->runTask('Checking MySQL', function () {
            return $this->commandExecutor->runQuietly(
                Mysql::make('-e', 'SELECT 1')
            );
        });
    }

    protected function runCommand(\Closure $closure)
    {
        return ! $this->option('no-wait')
            ? $this->waitForCommand($closure)
            : $closure();
    }

    protected function waitForCommand(\Closure $closure)
    {
        while ($exitCode = $closure()) {
            if ($this->seconds++ > $this->option('timeout')) {
                $this->error('Timed out waiting the command to finish');

                return 1;
            }

            sleep(1);
        }

        return $exitCode;
    }
}
