<?php

namespace Tests\Feature;

use Tests\TestCase;

class StartTest extends TestCase
{
    public function testStart()
    {
        $this->artisan('start')->assertExitCode(0);

        $this->assertDockerCompose('up -d');
    }
}
