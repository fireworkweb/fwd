<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Checker;
use App\CommandExecutor;

class CheckerTest extends TestCase
{
    public function testDockerVersion()
    {
        $this->mockCommandExecutorOutput(0, Checker::DOCKER_MIN_VERSION);

        $checker = resolve(Checker::class);

        $this->assertEquals($checker->dockerVersion(), Checker::DOCKER_MIN_VERSION);
    }

    public function testDockerApiVersion()
    {
        $this->mockCommandExecutorOutput(0, Checker::DOCKER_API_MIN_VERSION);

        $checker = resolve(Checker::class);

        $this->assertEquals($checker->dockerApiVersion(), Checker::DOCKER_API_MIN_VERSION);
    }

    public function testDockerComposeVersion()
    {
        $this->mockCommandExecutorOutput(0, Checker::DOCKER_COMPOSE_MIN_VERSION);

        $checker = resolve(Checker::class);

        $this->assertEquals($checker->dockerComposeVersion(), Checker::DOCKER_COMPOSE_MIN_VERSION);
    }

    public function testValidDockerVersion()
    {
        $this->mockCommandExecutorOutput(0, Checker::DOCKER_MIN_VERSION);

        $checker = resolve(Checker::class);

        $this->assertTrue($checker->checkDocker());
    }

    public function testValidNewerDockerVersion()
    {
        $this->mockCommandExecutorOutput(0, '19.03.12');

        $checker = resolve(Checker::class);

        $this->assertTrue($checker->checkDocker());
    }

    public function testInvalidDockerVersion()
    {
        $this->mockCommandExecutorOutput(0, '0.0.0');

        $checker = resolve(Checker::class);

        $this->assertFalse($checker->checkDocker());
    }

    public function testValidDockerApiVersion()
    {
        $this->mockCommandExecutorOutput(0, Checker::DOCKER_API_MIN_VERSION);

        $checker = resolve(Checker::class);

        $this->assertTrue($checker->checkDockerApi());
    }

    public function testInvalidDockerApiVersion()
    {
        $this->mockCommandExecutorOutput(0, '0.0.0');

        $checker = resolve(Checker::class);

        $this->assertFalse($checker->checkDockerApi());
    }

    public function testValidDockerComposeVersion()
    {
        $this->mockCommandExecutorOutput(0, Checker::DOCKER_COMPOSE_MIN_VERSION);

        $checker = resolve(Checker::class);

        $this->assertTrue($checker->checkDockerCompose());
    }

    public function testInvalidDockerComposeVersion()
    {
        $this->mockCommandExecutorOutput(0, '0.0.0');

        $checker = resolve(Checker::class);

        $this->assertFalse($checker->checkDockerCompose());
    }

    public function testErrorDockerVersionCommand()
    {
        $this->mockCommandExecutorOutput(1);

        $checker = resolve(Checker::class);

        $this->assertFalse($checker->checkDocker());
    }

    public function testErrorDockerApiVersionCommand()
    {
        $this->mockCommandExecutorOutput(1);

        $checker = resolve(Checker::class);

        $this->assertFalse($checker->checkDockerApi());
    }

    public function testErrorDockerComposeVersionCommand()
    {
        $this->mockCommandExecutorOutput(1);

        $checker = resolve(Checker::class);

        $this->assertFalse($checker->checkDockerCompose());
    }

    public function testDockerisRunning()
    {
        $this->mockCommandExecutorOutput(0);

        $checker = resolve(Checker::class);

        $this->assertTrue($checker->checkDockerIsRunning());
    }

    public function testDockerIsNotRunning()
    {
        $this->mockCommandExecutorOutput(0);

        $checker = resolve(Checker::class);

        $this->assertTrue($checker->checkDockerIsRunning());
    }

    protected function mockCommandExecutorOutput(int $exitCode, string $output = '')
    {
        $this->mock(CommandExecutor::class, function ($mock) use ($exitCode, $output) {
            $mock->shouldReceive('runQuietly')
                ->andReturn($exitCode);

            $mock->shouldReceive('getOutputBuffer')
                ->andReturn($output);
        });
    }
}
