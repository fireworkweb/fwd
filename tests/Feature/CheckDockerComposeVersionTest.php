<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\CommandExecutor;
use App\Commands\CheckDockerComposeVersion;
use App\Environment;

class CheckDockerComposeVersionTest extends TestCase
{
    public function testDockerComposeVersionEqualsMin()
    {
        $this->setDockerVersionMessage(CheckDockerComposeVersion::DOCKER_COMPOSE_MIN_VERSION);
        $this->artisan('check-docker-compose-version')->assertExitCode(0);
    }

    public function testDockerComposeVersionLessThanMin()
    {
        $this->setDockerVersionMessage('0.0');
        $this->artisan('check-docker-compose-version')->assertExitCode(1);
    }

    public function testDockerComposeVersionGreaterThanMin()
    {
        $this->setDockerVersionMessage('100.0');
        $this->artisan('check-docker-compose-version')->assertExitCode(0);
    }

    public function testCheckDockerComposeVersion()
    {
        $this->testDockerComposeVersionEqualsMin();

        $this->assertDockerCompose('version --short');
    }

    public function testWindows()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.windows');

        $this->testCheckDockerComposeVersion();
    }

    private function setDockerVersionMessage($version): void
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn($version);
    }
}
