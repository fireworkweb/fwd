<?php

namespace Tests\Feature;

use Tests\TestCase;

class BuddyTest extends TestCase
{
    public function testBuddy()
    {
        $this->artisan('buddy')->assertExitCode(0);

        $this->assertDockerRun('fireworkweb/node:12-qa buddy src/');
    }

    public function testBuddyCustom()
    {
        $this->artisan('buddy app/')->assertExitCode(0);

        $this->assertDockerRun("fireworkweb/node:12-qa buddy 'app/'");
    }
}
