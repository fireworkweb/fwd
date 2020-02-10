<?php

namespace Tests\Feature;

use Tests\TestCase;

class RunTest extends TestCase
{
    public function testDockerRunPhp()
    {
        $this->artisan('run fireworkweb/app:7.2-alpine php -v')->assertExitCode(0);

        $this->assertDockerRun("'fireworkweb/app:7.2-alpine' php -v");
    }

    public function testDockerRunNode()
    {
        $this->artisan('run fireworkweb/node:alpine node -v')->assertExitCode(0);

        $this->assertDockerRun("'fireworkweb/node:alpine' node -v");
    }
}
