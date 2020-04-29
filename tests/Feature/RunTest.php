<?php

namespace Tests\Feature;

use Tests\TestCase;

class RunTest extends TestCase
{
    public function testDockerRunPhp()
    {
        $this->artisan('run fireworkweb/php:7.4 php -v')->assertExitCode(0);

        $this->assertDockerRun("'fireworkweb/php:7.4' php -v");
    }

    public function testDockerRunNode()
    {
        $this->artisan('run fireworkweb/node:12 node -v')->assertExitCode(0);

        $this->assertDockerRun("'fireworkweb/node:12' node -v");
    }
}
