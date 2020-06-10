<?php

namespace App;

use App\Builder\Builder;
use App\Builder\Docker;
use App\Builder\DockerCompose;

class Checker
{
    const DOCKER_MIN_VERSION = '18.09';
    const DOCKER_API_MIN_VERSION = '1.25';
    const DOCKER_COMPOSE_MIN_VERSION = '1.23';

    /** @var CommandExecutor $commandExecutor */
    protected $commandExecutor;

    /** @var string $dockerVersion */
    protected $dockerVersion;

    /** @var string $dockerApiVersion */
    protected $dockerApiVersion;

    /** @var string $dockerComposeVersion */
    protected $dockerComposeVersion;

    public function __construct(CommandExecutor $commandExecutor)
    {
        $this->commandExecutor = $commandExecutor;
    }

    public function dockerVersion()
    {
        if (is_null($this->dockerVersion)) {
            $this->dockerVersion = $this->version(
                Docker::make("version --format '{{.Server.Version}}'")
            );
        }

        return $this->dockerVersion;
    }

    public function dockerApiVersion()
    {
        if (is_null($this->dockerApiVersion)) {
            $this->dockerApiVersion = $this->version(
                Docker::make("version --format '{{.Server.APIVersion}}'")
            );
        }

        return $this->dockerApiVersion;
    }

    public function dockerComposeVersion()
    {
        if (is_null($this->dockerComposeVersion)) {
            $this->dockerComposeVersion = $this->version(
                DockerCompose::make('version', '--short')
            );
        }

        return $this->dockerComposeVersion;
    }

    public function checkDocker(): bool
    {
        return version_compare(
            $this->dockerVersion(),
            self::DOCKER_MIN_VERSION,
            '>='
        );
    }

    public function checkDockerApi(): bool
    {
        return version_compare(
            $this->dockerApiVersion(),
            self::DOCKER_API_MIN_VERSION,
            '>='
        );
    }

    public function checkDockerCompose(): bool
    {
        return version_compare(
            $this->dockerComposeVersion(),
            self::DOCKER_COMPOSE_MIN_VERSION,
            '>='
        );
    }

    public function checkDockerIsRunning(): bool
    {
        $exitCode = $this->commandExecutor->runQuietly(
            Docker::make('info'),
            false
        );

        return $exitCode === 0;
    }

    protected function version(Builder $builder)
    {
        $exitCode = $this->commandExecutor->runQuietly($builder);

        if ($exitCode) {
            return false;
        }

        return $this->commandExecutor->getOutputBuffer() ?: false;
    }
}
