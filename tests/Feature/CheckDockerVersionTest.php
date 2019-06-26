<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\CommandExecutor;
use App\Commands\CheckDockerVersion;
use App\Environment;

class CheckDockerVersionTest extends TestCase
{
    public function testDockerVersionEqualsMin()
    {
        $this->setDockerVersionMessage(CheckDockerVersion::DOCKER_MIN_VERSION);
        $this->artisan('check-docker-version')->assertExitCode(0);
    }

    public function testDockerVersionLessThanMin()
    {
        $this->setDockerVersionMessage('0.0');
        $this->artisan('check-docker-version')->assertExitCode(1);
    }

    public function testDockerVersionGreaterThanMin()
    {
        $this->setDockerVersionMessage('100.0');
        $this->artisan('check-docker-version')->assertExitCode(0);
    }

    public function testCheckDockerVersion()
    {
        $this->testDockerVersionEqualsMin();

        $this->assertDocker("version --format '{{.Server.Version}}'");
    }

    public function testWindows()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.windows');

        $this->testCheckDockerVersion();
    }

    private function setDockerVersionMessage($version): void
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn($version);
    }
}
