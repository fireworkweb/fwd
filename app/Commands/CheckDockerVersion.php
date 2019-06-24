<?php

namespace App\Commands;

use App\CommandExecutor;
use LaravelZero\Framework\Commands\Command;
use App\Builder\Docker;
use App\Commands\Traits\RunTask;

class CheckDockerVersion extends Command
{
    const DOCKER_MIN_VERSION = '18.09';

    use RunTask;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'check-docker-version';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Checks that the docker version is compatible';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CommandExecutor $executor)
    {
        return $this->runTask('Checking Docker version', function () use ($executor) {
            return $this->checkDockerVersion($executor);
        });
    }

    protected function checkDockerVersion(CommandExecutor $executor): int
    {
        if ($executor->runQuietly(new Docker('-v'))) {
            return 1;
        }

        $output = $executor->getOutputBuffer();
        $matches = [];

        if (! preg_match('/(?:(\d+)\.)(?:(\d+)\.)?(\*|\d+)/', $output, $matches)) {
            $this->error('Docker version could not be parsed.');
            return 1;
        }

        // $matches[0] = current version
        $isValidVersion = version_compare($matches[0], self::DOCKER_MIN_VERSION, '>=');

        if (! $isValidVersion) {
            $this->error('Docker version must be >= ' . self::DOCKER_MIN_VERSION);
        }

        return $isValidVersion ? 0 : 1;
    }
}
