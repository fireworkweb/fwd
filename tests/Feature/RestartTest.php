<?php

namespace Tests\Feature;

use App\Checker;
use Tests\TestCase;

class RestartTest extends TestCase
{
    public function testRestart()
    {
        $this->mockChecker();

        $this->artisan('restart')->assertExitCode(0);

        $this->assertDockerCompose('down');
        $this->assertDockerCompose('up -d --force-recreate ' . env('FWD_START_DEFAULT_SERVICES'));
        $this->assertDocker('network create --attachable ' . env('FWD_NETWORK'));
    }

    public function testRestartWithAll()
    {
        $this->mockChecker();

        $this->artisan('restart --all')->assertExitCode(0);

        $this->assertDockerCompose('down');
        $this->assertDockerCompose('up -d --force-recreate');
        $this->assertDocker('network create --attachable ' . env('FWD_NETWORK'));
    }

    public function testRestartWithSpecificServices()
    {
        $this->mockChecker();

        $this->artisan('restart --services=chromedriver')->assertExitCode(0);

        $this->assertDockerCompose('down');
        $this->assertDockerCompose('up -d --force-recreate chromedriver');
    }

    public function testRestartOldVersionAllDependencies()
    {
        $this->mockChecker(
            '0.0.0',
            '0.0.0',
            '0.0.0'
        );

        $this->artisan('restart')->assertExitCode(1);
    }

    public function testRestartOldVersionDockerDependency()
    {
        $this->mockChecker(
            '0.0.0',
            Checker::DOCKER_API_MIN_VERSION,
            Checker::DOCKER_COMPOSE_MIN_VERSION
        );

        $this->artisan('restart')->assertExitCode(1);
    }

    public function testRestartOldVersionDockerAPIDependency()
    {
        $this->mockChecker(
            Checker::DOCKER_MIN_VERSION,
            '0.0.0',
            Checker::DOCKER_COMPOSE_MIN_VERSION
        );

        $this->artisan('restart')->assertExitCode(1);
    }

    public function testRestartOldVersionDockerComposeDependency()
    {
        $this->mockChecker(
            Checker::DOCKER_MIN_VERSION,
            Checker::DOCKER_API_MIN_VERSION,
            '0.0.0'
        );

        $this->artisan('restart')->assertExitCode(1);
    }

    protected function mockChecker(
        $dockerVersion = Checker::DOCKER_MIN_VERSION,
        $dockerApiVersion = Checker::DOCKER_API_MIN_VERSION,
        $dockerComposeVersion = Checker::DOCKER_COMPOSE_MIN_VERSION
    ) {
        $this->mock(Checker::class, function ($mock) use (
            $dockerVersion,
            $dockerApiVersion,
            $dockerComposeVersion
        ) {
            $mock->shouldReceive('dockerVersion')
                ->andReturn($dockerVersion);

            $mock->shouldReceive('dockerApiVersion')
                ->andReturn($dockerApiVersion);

            $mock->shouldReceive('dockerComposeVersion')
                ->andReturn($dockerComposeVersion);
        })->makePartial();
    }
}
