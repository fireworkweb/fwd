<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Environment;

class PrepareDuskTest extends TestCase
{
    public function testDusk()
    {
        $this->artisan('prepare-dusk')->assertExitCode(0);

        $this->assertCommandCalled('reset', ['.env.dusk.local']);
    }
}
