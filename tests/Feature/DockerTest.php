<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Environment;

class DockerTest extends TestCase
{
    public function testDocker()
    {
        $this->artisan('docker')->assertExitCode(0);

        $this->assertDocker('ps');
    }

    public function testCustomDocker()
    {
        $this->artisan('docker build')->assertExitCode(0);

        $this->assertDocker('build');
    }

    public function testWindows()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.windows');

        $this->artisan('docker ps')->assertExitCode(0);
        $this->artisan('docker-compose ps')->assertExitCode(0);

        $this->assertDocker('ps');
        $this->assertDockerCompose('ps');
    }
}
