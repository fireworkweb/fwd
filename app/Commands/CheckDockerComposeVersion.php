<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Builder\DockerCompose;
use App\CommandExecutor;
use App\Commands\Traits\RunTask;

class CheckDockerComposeVersion extends Command
{
    const COMPOSE_MIN_VERSION = '1.24';

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
        if ($executor->runQuietly(new DockerCompose('-v'))) {
            return 1;
        }

        $output = $executor->getOutputBuffer();
        $matches = [];

        if (! preg_match('/(?:(\d+)\.)(?:(\d+)\.)?(\*|\d+)/', $output, $matches)) {
            $this->error('Docker-compose version could not be parsed.');
            return 1;
        }

        // $matches[0] = full version
        $isValidVersion = version_compare($matches[0], self::COMPOSE_MIN_VERSION, '>=');

        if (! $isValidVersion) {
            $this->error('Docker-compose version must be >= ' . self::COMPOSE_MIN_VERSION);
        }

        return $isValidVersion ? 0 : 1;
    }
}
