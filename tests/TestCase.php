<?php

namespace Tests;

use App\Process;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertDockerComposeExec(string $command)
    {
        $this->assertProcessRun([
            sprintf('docker-compose -p %s exec', basename(getcwd())),
            $command,
        ]);
    }

    protected function assertDockerRun(string $command)
    {
        $this->assertProcessRun([
            'docker run --rm -it',
            sprintf('-v %s:/app:cached', getcwd()),
            sprintf('-v %s:/home/developer/.ssh/id_rsa:cached', env('FWD_SSH_KEY_PATH')),
            sprintf('-e ASUSER=%s', env('FWD_ASUSER')),
            $command,
        ]);
    }

    protected function assertProcessRun(array $command)
    {
        $command = implode(' ', array_filter($command));

        $this->mock(Process::class, function ($mock) use ($command) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('run')
                ->once()
                ->withArgs(function($run) use ($command) {
                    $this->assertEquals($command, $run);

                    return true;
                })
                ->andReturnNull();
        })->makePartial();
    }

    protected function mockProcess()
    {
        $this->mock(Process::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('run')
                ->andReturnNull();
        })->makePartial();
    }
}
