<?php

namespace Tests;

use App\Process;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $asUser = null;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setup();

        $this->mockProcess();

        // resets intended execution user
        $this->setAsUser(null);
    }

    protected function setAsUser($user)
    {
        $this->asUser = $user;

        return $this;
    }

    protected function asFWDUser()
    {
        return $this->setAsUser(env('FWD_ASUSER'));
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
        $params = [
            env('FWD_DOCKER_COMPOSE_BIN', 'docker-compose'),
            sprintf('-p %s exec', basename(getcwd())),

        ];

        if (! empty($this->asUser)) {
            $params[] = '--user';
            $params[] = $this->asUser;
        }

        $params[] = $this->buildCommand($command);

        $this->assertProcessRun($params);
    }

    protected function assertDockerRun(...$command)
    {
        $this->assertProcessRun([
            env('FWD_DOCKER_BIN', 'docker'),
            'run --rm -it --init -w /app',
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
