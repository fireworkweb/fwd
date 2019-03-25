<?php

namespace Tests\Feature;

use Tests\TestCase;

class BuildTest extends TestCase
{
    public function testBuild()
    {
        $this->artisan('build')->assertExitCode(0);

        $this->assertDockerCompose('build');
    }

    public function testBuildCustom()
    {
        $this->artisan('build --no-cache --pull')->assertExitCode(0);

        $this->assertDockerCompose('build --no-cache --pull');
    }
}
