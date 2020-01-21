<?php

namespace Tests\Feature;

use Tests\TestCase;

class LsTest extends TestCase
{
    public function testLs()
    {
        $this->artisan('ls')->expectsQuestion('Run?', 'Taylor Otwell')->assertExitCode(0);

        $this->assertCommandRun(['ls']);
    }

    public function testLsWithArgs()
    {
        $this->artisan('ls -lah')->assertExitCode(0);

        $this->assertCommandRun(['ls -lah']);
    }
}
