<?php

namespace App\Commands;

use App\CommandExecutor;
use App\Builder\DockerCompose;
use App\Commands\Traits\RunTask;
use LaravelZero\Framework\Commands\Command;

class CheckDockerComposeVersion extends Command
{
    const DOCKER_COMPOSE_MIN_VERSION = '1.24';

    use RunTask;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'check-docker-compose-version';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Checks that the docker-compose version is compatible';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CommandExecutor $executor)
    {
        return $this->runTask('Checking docker-compose version', function () use ($executor) {
            return $this->checkDockerComposeVersion($executor);
        });
    }

    protected function checkDockerComposeVersion(CommandExecutor $executor): int
    {
        $exitCode = $executor->runQuietly(new DockerCompose('version --short'));

        $output = $executor->getOutputBuffer();

        $isValidVersion = $exitCode === 0 && $output && version_compare($output, self::DOCKER_COMPOSE_MIN_VERSION, '>=');

        if (! $isValidVersion) {
            $this->error('Docker-compose version must be >= ' . self::DOCKER_COMPOSE_MIN_VERSION);
        }

        return $isValidVersion ? 0 : 1;
    }
}
