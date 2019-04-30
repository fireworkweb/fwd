<?php

namespace Tests;

use App\Process;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setup();

        $this->mockProcess();
    }

    protected function assertDocker(...$command)
    {
        $this->assertProcessRun([
            env('FWD_DOCKER_BIN', 'docker'),
            $this->buildCommand($command),
        ]);
    }

    protected function assertDockerCompose(...$command)
    {
        $this->assertProcessRun([
            env('FWD_DOCKER_COMPOSE_BIN', 'docker-compose'),
            sprintf('-p %s', basename(getcwd())),
            $this->buildCommand($command),
        ]);
    }

    protected function assertDockerComposeExec(...$command)
    {
        $this->assertProcessRun([
            env('FWD_DOCKER_COMPOSE_BIN', 'docker-compose'),
            sprintf('-p %s exec', basename(getcwd())),
            $this->buildCommand($command),
        ]);
    }

    protected function assertDockerRun(...$command)
    {
        $this->assertProcessRun([
            env('FWD_DOCKER_BIN', 'docker'),
            'run --rm -it -w /app',
            sprintf('-v %s:/app:cached', env('FWD_CONTEXT_PATH')),
            sprintf('-v %s:/home/developer/.ssh/id_rsa:cached', env('FWD_SSH_KEY_PATH')),
            sprintf('-e ASUSER=%s', env('FWD_ASUSER')),
            $this->buildCommand($command),
        ]);
    }

    protected function assertProcessRun(array $command)
    {
        $command = $this->buildCommand($command);

        static::assertTrue(app(Process::class)->hasCommand($command),
            'Failed asserting that this command was called: ' . $command);
    }

    protected function mockProcess()
    {
        $this->mock(Process::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('run')
                ->andReturn(0);
        })->makePartial();
    }

    protected function buildCommand(array $command)
    {
        return trim(implode(' ', array_filter($command)));
    }
}
