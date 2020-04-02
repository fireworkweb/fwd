<?php

namespace Tests\Feature;

use Tests\TestCase;

class YarnTest extends TestCase
{
    public function testYarn()
    {
        $this->artisan('yarn')->assertExitCode(0);

        $this->assertDockerRun('fireworkweb/node:12 yarn');
    }

    public function testYarnCustom()
    {
        $this->artisan('yarn install')->assertExitCode(0);

        $this->assertDockerRun('fireworkweb/node:12 yarn install');
    }
}
