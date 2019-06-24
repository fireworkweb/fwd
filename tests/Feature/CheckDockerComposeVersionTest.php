<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\CommandExecutor;

class CheckDockerComposeVersionTest extends TestCase
{

    public function testDockerComposeVersionEqualsMin()
    {
        $this->setDockerVersionMessage('1.24');
        $this->artisan('check-docker-compose-version')->assertExitCode(0);
    }

    public function testDockerComposeVersionLessThanMin()
    {
        $this->setDockerVersionMessage('1.23');
        $this->artisan('check-docker-compose-version')->assertExitCode(1);
    }

    public function testDockerComposeVersionGreaterThanMin()
    {
        $this->setDockerVersionMessage('1.25');
        $this->artisan('check-docker-compose-version')->assertExitCode(0);
    }

    private function setDockerVersionMessage($version): void
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn("Docker-compose version $version.");
    }
}
