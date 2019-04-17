<?php

namespace Tests\Feature;

use Tests\TestCase;

class DockerComposeTest extends TestCase
{
    public function testDockerCompose()
    {
        $this->artisan('docker-compose')->assertExitCode(0);

        $this->assertDockerCompose('ps');
    }

    public function testCustomDockerCompose()
    {
        $this->artisan('docker-compose up -d')->assertExitCode(0);

        $this->assertDockerCompose('up -d');
    }
}
