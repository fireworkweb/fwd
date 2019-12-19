<?php

namespace Tests\Feature;

use Tests\TestCase;

class StopTest extends TestCase
{
    public function testStop()
    {
        $this->artisan('stop')->assertExitCode(0);

        $this->assertDockerCompose('down');
    }

    public function testStopAndPurge()
    {
        $this->artisan('stop --purge')->assertExitCode(0);

        $this->assertDockerCompose('down --volumes');
    }
}
