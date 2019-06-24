<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\CommandExecutor;
use App\Environment;

class StartTest extends TestCase
{
    public function testStart()
    {
        resolve(CommandExecutor::class)
            ->shouldReceive('getOutputBuffer')
            ->andReturn('18.09', '1.24');

        $this->artisan('start')->assertExitCode(0);

        $this->assertDockerCompose('up -d');
    }
}
