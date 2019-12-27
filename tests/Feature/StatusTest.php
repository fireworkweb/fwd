<?php

namespace Tests\Feature;

use Tests\TestCase;

class StatusTest extends TestCase
{
    public function testStart()
    {
        $this->artisan('status')->assertExitCode(0);

        $this->assertDockerCompose('ps --services');
        $this->assertDockerCompose('ps');
    }
}
