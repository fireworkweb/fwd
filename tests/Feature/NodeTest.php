<?php

namespace Tests\Feature;

use Tests\TestCase;

class NodeTest extends TestCase
{
    public function testNode()
    {
        $this->artisan('node')->assertExitCode(0);

        $this->assertDockerRun('fireworkweb/node:alpine node -v');
    }

    public function testNodeCustom()
    {
        $this->artisan('node index.js')->assertExitCode(0);

        $this->assertDockerRun("fireworkweb/node:alpine node 'index.js'");
    }
}
