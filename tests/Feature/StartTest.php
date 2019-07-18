<?php

namespace Tests\Feature;

use App\Checker;
use Tests\TestCase;
use App\CommandExecutor;

class StartTest extends TestCase
{
    public function testStart()
    {
        $this->mockChecker(
            Checker::DOCKER_MIN_VERSION,
            Checker::DOCKER_API_MIN_VERSION,
            Checker::DOCKER_COMPOSE_MIN_VERSION
        );

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
        $dockerVersion,
        $dockerApiVersion,
        $dockerComposeVersion
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

    // public function testStartTimeout()
    // {
    //     resolve(CommandExecutor::class)
    //         ->shouldReceive('getOutputBuffer')
    //         ->andReturn(
    //             CheckDockerVersion::DOCKER_MIN_VERSION,
    //             CheckDockerComposeVersion::DOCKER_COMPOSE_MIN_VERSION
    //         );

    //     $this->artisan('start --timeout=1')
    //         ->expectsOutput('Timed out waiting the command to finish')
    //         ->assertExitCode(1);
    // }
}
