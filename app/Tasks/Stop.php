<?php

namespace App\Tasks;

use App\Builder\DockerCompose;

class Stop extends Task
{
    /** @var bool $purge */
    protected $purge = true;

    /** @var string $services */
    protected $services;

    public function run(...$args): int
    {
        $tasks = [
            [$this, 'destroyContainers'],
        ];

        return $this->runCallables($tasks);
    }

    public function purge(bool $purge): self
    {
        $this->purge = $purge;

        return $this;
    }

    public function services(string $services): self
    {
        $this->services = $services;

        return $this;
    }

    public function destroyContainers(): int
    {
        return $this->runTask('Turning off fwd', function () {
            $args[] = 'down';

            if ($this->purge) {
                $args[] = '--volumes';
                $args[] = '--remove-orphans';
            }

            if (! is_null($this->services)) {
                $args[] = ($this->services ?: env('FWD_START_DEFAULT_SERVICES'));
            }

            return $this->runCommandWithoutOutput(
                DockerCompose::makeWithDefaultArgs(...$args),
                false
            );
        });
    }
}
