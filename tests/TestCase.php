<?php

namespace Tests;

use App\Process;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

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

    protected function assertDockerCompose(...$command)
    {
        $this->assertProcessRun([
            sprintf('docker-compose -p %s', basename(getcwd())),
            $this->buildCommand($command),
        ]);
    }

    protected function assertDockerComposeExec(...$command)
    {
        $this->assertProcessRun([
            sprintf('docker-compose -p %s exec', basename(getcwd())),
            $this->buildCommand($command),
        ]);
    }

    protected function assertDockerRun(...$command)
    {
        $this->assertProcessRun([
            'docker run --rm -it',
            sprintf('-v %s:/app:cached', getcwd()),
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
                ->andReturnNull();
        })->makePartial();
    }

    protected function buildCommand(array $command)
    {
        return implode(' ', array_filter($command));
    }
}
