<?php

namespace Tests\Feature;

use App\Process;
use Tests\TestCase;
use App\CommandExecutor;
use App\Commands\CheckDockerVersion;
use App\Commands\CheckDockerComposeVersion;

class StartTest extends TestCase
{
    public function testStart()
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn(CheckDockerVersion::DOCKER_MIN_VERSION, CheckDockerComposeVersion::DOCKER_COMPOSE_MIN_VERSION);

        $this->artisan('start')->assertExitCode(0);

        $this->assertDockerCompose('up -d');
    }

    public function testStartOldVersionAllDependencies()
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn('0.0.0');

        $this->artisan('start')->assertExitCode(1);
    }

    public function testStartOldVersionDockerDependency()
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn('0.0.0', CheckDockerComposeVersion::DOCKER_COMPOSE_MIN_VERSION);

        $this->artisan('start')->assertExitCode(1);
    }

    public function testStartOldVersionDockerComposeDependency()
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn(CheckDockerVersion::DOCKER_MIN_VERSION, '0.0.0');

        $this->artisan('start')->assertExitCode(1);
    }

    public function testStartTimeout()
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn(CheckDockerVersion::DOCKER_MIN_VERSION, CheckDockerComposeVersion::DOCKER_COMPOSE_MIN_VERSION);

        resolve(Process::class)
            ->shouldReceive('dockerCompose')
            ->withArgs(function ($command) {
                return $command === 'ps';
            })
            ->andReturn(1);

        $this->artisan('start --timeout=1')
            ->expectsOutput('Timed out waiting the command to finish')
            ->assertExitCode(1);
    }
}
