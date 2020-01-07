<?php

namespace App\Tasks;

use App\Builder\Docker;
use App\Builder\DockerCompose;

class Stop extends Task
{
    /** @var bool $purge */
    protected $purge = true;

    public function run(...$args): int
    {
        $tasks = [
            [$this, 'destroyContainers'],
            [$this, 'handleNetwork'],
        ];

        return $this->runCallables($tasks);
    }

    public function purge(bool $purge): self
    {
        $this->purge = $purge;

        return $this;
    }

    public function handleNetwork(): int
    {
        return $this->runTask('Destroy network', function () {
            return $this->runCommandWithoutOutput(
                Docker::makeWithDefaultArgs('network', 'rm', env('FWD_NETWORK'))
            );
        });
    }

    public function destroyContainers(): int
    {
        return $this->runTask('Turning off fwd', function () {
            $args[] = 'down';

            if ($this->purge) {
                $args[] = '--volumes';
                $args[] = '--remove-orphans';
            }

            return $this->runCommandWithoutOutput(
                DockerCompose::makeWithDefaultArgs(...$args),
                false
            );
        });
    }
}
