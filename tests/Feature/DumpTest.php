<?php

namespace Tests\Feature;

use Tests\TestCase;

class DumpTest extends TestCase
{
    public function testDump()
    {
        $this->artisan('dump')->assertExitCode(0);

        $this->assertCommandCalled('dump');
    }
}
