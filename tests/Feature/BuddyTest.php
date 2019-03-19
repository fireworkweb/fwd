<?php

namespace Tests\Feature;

use Tests\TestCase;

class BuddyTest extends TestCase
{
    public function testBuddy()
    {
        $this->assertDockerRun('fireworkweb/node:qa buddy src/');

        $this->artisan('buddy')->assertExitCode(0);
    }

    public function testBuddyCustom()
    {
        $this->assertDockerRun("fireworkweb/node:qa buddy 'app/'");

        $this->artisan('buddy app/')->assertExitCode(0);
    }
}
