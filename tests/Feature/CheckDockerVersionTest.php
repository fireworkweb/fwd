<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\CommandExecutor;

class CheckDockerVersionTest extends TestCase
{
    public function testDockerVersionEqualsMin()
    {
        $this->setDockerVersionMessage('18.09');
        $this->artisan('check-docker-version')->assertExitCode(0);
    }

    public function testDockerVersionLessThanMin()
    {
        $this->setDockerVersionMessage('15.0');
        $this->artisan('check-docker-version')->assertExitCode(1);
    }

    public function testDockerVersionGreaterThanMin()
    {
        $this->setDockerVersionMessage('20.00');
        $this->artisan('check-docker-version')->assertExitCode(0);
    }

    private function setDockerVersionMessage($version): void
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn($version);
    }
}
