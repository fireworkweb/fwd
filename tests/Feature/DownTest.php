<?php

namespace Tests\Feature;

use Tests\TestCase;

class DownTest extends TestCase
{
    public function testDown()
    {
        $this->artisan('down')->assertExitCode(0);

        $this->assertDockerCompose('down');
    }

    public function testDownCustom()
    {
        $this->artisan('down something')->assertExitCode(0);

        $this->assertDockerCompose('down something');
    }
}
