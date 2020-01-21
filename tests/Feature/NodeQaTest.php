<?php

namespace Tests\Feature;

use Tests\TestCase;

class NodeQaTest extends TestCase
{
    public function testNodeQa()
    {
        $this->artisan('node-qa node -v')->assertExitCode(0);

        $this->assertDockerRun('fireworkweb/node:qa node -v');
    }

    public function testNodeQaCustom()
    {
        $this->artisan('node-qa eslint')->assertExitCode(0);

        $this->assertDockerRun('fireworkweb/node:qa eslint');
    }
}
