<?php

namespace Tests\Feature;

use App\Checker;
use Tests\TestCase;

class StartTest extends TestCase
{
    public function testStart()
    {
        $this->mockChecker();

        $this->artisan('start')->assertExitCode(0);

        $this->assertDockerCompose('up -d');
    }

    public function testStartOldVersionAllDependencies()
    {
        $this->mockChecker(
            '0.0.0',
            '0.0.0',
            '0.0.0'
        );

        $this->artisan('start')->assertExitCode(1);
    }

    public function testStartOldVersionDockerDependency()
    {
        $this->mockChecker(
            '0.0.0',
            Checker::DOCKER_API_MIN_VERSION,
            Checker::DOCKER_COMPOSE_MIN_VERSION
        );

        $this->artisan('start')->assertExitCode(1);
    }

    public function testStartOldVersionDockerAPIDependency()
    {
        $this->mockChecker(
            Checker::DOCKER_MIN_VERSION,
            '0.0.0',
            Checker::DOCKER_COMPOSE_MIN_VERSION
        );

        $this->artisan('start')->assertExitCode(1);
    }

    public function testStartOldVersionDockerComposeDependency()
    {
        $this->mockChecker(
            Checker::DOCKER_MIN_VERSION,
            Checker::DOCKER_API_MIN_VERSION,
            '0.0.0'
        );

        $this->artisan('start')->assertExitCode(1);
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
