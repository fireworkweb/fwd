<?php

namespace Tests\Feature;

use Tests\TestCase;

class StopTest extends TestCase
{
    public function testStop()
    {
        $this->artisan('stop')->assertExitCode(0);

        $this->assertDockerCompose('down ' . env('FWD_START_DEFAULT_SERVICES'));
    }

    public function testStopAndPurge()
    {
        $this->artisan('stop --purge')->assertExitCode(0);

        $this->assertDockerCompose('down --volumes --remove-orphans ' . env('FWD_START_DEFAULT_SERVICES'));
    }

    public function testStopWithAll()
    {
        $this->artisan('stop --all')->assertExitCode(0);

        $this->assertDockerCompose('down');
    }

    public function testStartWithSpecificServices()
    {
        $this->artisan('stop --services=chromedriver')->assertExitCode(0);

        $this->assertDockerCompose('down chromedriver');
    }
}
