<?php

namespace Tests\Feature;

use Tests\TestCase;

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
}
