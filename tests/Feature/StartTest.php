<?php

namespace Tests\Feature;

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

    public function testStartOldVersionDependencies()
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn('0.0.0');

        $this->artisan('start')->assertExitCode(1);
    }
}
