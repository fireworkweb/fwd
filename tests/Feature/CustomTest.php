<?php

namespace Tests\Feature;

use Tests\TestCase;

class CustomTest extends TestCase
{
    public function testCustom()
    {
        $this->artisan('custom')->assertExitCode(0);

        $this->assertCommandRun(['echo custom']);
    }
}
