<?php

namespace App\Tasks;

use App\Builder\Docker;
use App\Builder\DockerCompose;
use App\Builder\Escaped;
use App\Builder\Mysql;
use App\Checker;

class Start extends Task
{
    /** @var int $timeout */
    protected $timeout = 60; // seconds

    /** @var bool $checks */
    protected $checks = true;

    /** @var string $services */
    protected $services;

    /** @var bool $all */
    protected $all;

    public function run(...$args): int
    {
        $tasks = [
            [$this, 'handleNetworkTask'],
            [$this, 'startContainers'],
        ];

        if ($this->checks && env('FWD_START_CHECK')) {
            array_unshift($tasks, [$this, 'checkDependencies']);
            $tasks[] = [$this, 'checkDatabase'];
        }

        return $this->runCallables($tasks);
    }

    public function services(string $services): self
    {
        $this->services = $services;

        return $this;
    }

    public function all(bool $all): self
    {
        $this->all = $all;

        return $this;
    }

    public function timeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function checks(bool $checks): self
    {
        $this->checks = $checks;

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

            return 0;
        });
    }

    public function handleNetworkTask(): int
    {
        return $this->runTask('Setting up network', function () {
            return $this->handleNetwork();
        });
    }

    public function handleNetwork(): int
    {
        $command = Docker::make('network', 'ls', '-q', '-f', 'NAME=' . env('FWD_NETWORK'));
        $id = $this->getOutput($command);

        if (! empty($id)) {
            return 0;
        }

        return $this->runCommandWithoutOutput(
            Docker::makeWithDefaultArgs('network', 'create', '--attachable', env('FWD_NETWORK'))
        );
    }

    public function startContainers(): int
    {
        return $this->runTask('Starting fwd', function () {
            $services = ! is_null($this->services) && ! $this->all
                ? ($this->services ?: env('FWD_START_DEFAULT_SERVICES'))
                : null;

            return $this->runCommandWithoutOutput(
                DockerCompose::make('up', '-d', '--force-recreate', $services),
                false
            );
        });
    }

    public function checkDatabase()
    {
        return $this->runTask('Checking Database', function () {
            return $this->runCallableWaitFor(function () {
                return $this->runCommandWithoutOutput(
                    Mysql::make('-e', Escaped::make('SELECT 1')),
                    false
                );
            }, $this->timeout);
        });
    }
}
