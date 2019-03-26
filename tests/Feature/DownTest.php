<?php

namespace Tests\Feature;

use Tests\TestCase;

class DownTest extends TestCase
{
    public function testDockerCompose()
    {
        $this->artisan('down')->assertExitCode(0);

        $this->assertDockerCompose('down');
    }
}
