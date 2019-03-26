<?php

namespace Tests\Feature;

use Tests\TestCase;

class StopTest extends TestCase
{
    public function testStop()
    {
        $this->artisan('stop')->assertExitCode(0);

        $this->assertDockerCompose('stop');
    }

    public function testStopCustom()
    {
        $this->artisan('stop something')->assertExitCode(0);

        $this->assertDockerCompose('stop something');
    }
}
