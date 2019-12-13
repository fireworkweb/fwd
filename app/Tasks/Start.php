<?php

namespace App\Tasks;

use App\Builder\DockerCompose;
use App\Builder\Escaped;
use App\Builder\Mysql;
use App\Checker;

class Start extends Task
{
    /** @var int $timeout */
    protected $timeout = 60; // seconds

    /** @var string $services */
    protected $services;

    public function run(...$args): int
    {
        return $this->runCallables([
            [$this, 'checkDependencies'],
            [$this, 'dockerComposeUpD'],
            [$this, 'mysql'],
        ]);
    }

    public function services(string $services) : self
    {
        $this->services = $services;

        return $this;
    }

    public function timeout(int $timeout) : self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function checkDependencies()
    {
        return $this->runTask('Checking dependencies', function () {
            $checker = app(Checker::class);

            if (! $checker->checkDocker()) {
                $this->command->error(sprintf(
                    'Incompatible docker version (Current: %s Required: %s).',
                    $checker->dockerVersion(),
                    Checker::DOCKER_MIN_VERSION
                ));

                return 1;
            }

            if (! $checker->checkDockerApi()) {
                $this->command->error(sprintf(
                    'Incompatible docker api version (Current: %s Required: %s).',
                    $checker->dockerApiVersion(),
                    Checker::DOCKER_API_MIN_VERSION
                ));

                return 1;
            }

            if (! $checker->checkDockerCompose()) {
                $this->command->error(sprintf(
                    'Incompatible docker-compose version (Current: %s Required: %s).',
                    $checker->dockerComposeVersion(),
                    Checker::DOCKER_COMPOSE_MIN_VERSION
                ));

                return 1;
            }

            return $this->runCallableWaitFor(function () {
                return $this->runCommandWithoutOutput(
                    DockerCompose::make('ps'),
                    false
                );
            }, $this->timeout);
        });
    }

    public function dockerComposeUpD()
    {
        return $this->runTask('Starting fwd', function () {
            $services = ! is_null($this->services)
                ? ($this->services ?: env('FWD_START_DEFAULT_SERVICES'))
                : null;

            return $this->runCommandWithoutOutput(
                DockerCompose::make('up', '-d', $services),
                false
            );
        });
    }

    public function mysql()
    {
        return $this->runTask('Checking MySQL', function () {
            return $this->runCallableWaitFor(function () {
                return $this->runCommandWithoutOutput(
                    Mysql::make('-e', Escaped::make('SELECT 1')),
                    false
                );
            }, $this->timeout);
        });
    }
}
