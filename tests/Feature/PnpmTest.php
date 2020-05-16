<?php

namespace Tests\Feature;

use Tests\TestCase;

class PnpmTest extends TestCase
{
    public function testPnpm()
    {
        $this->artisan('pnpm')->assertExitCode(0);

        $this->assertDockerRun('fireworkweb/node:12 pnpm -v');
    }

    public function testPnpmCustom()
    {
        $this->artisan('pnpm install')->assertExitCode(0);

        $this->assertDockerRun('fireworkweb/node:12 pnpm install');
    }
}
