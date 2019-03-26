<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhpTest extends TestCase
{
    public function testPhp()
    {
        $this->artisan('php')->assertExitCode(0);

        $this->assertDockerComposeExec('app php -v');
    }

    public function testPhpInstall()
    {
        $this->artisan('php something')->assertExitCode(0);

        $this->assertDockerComposeExec('app something');
    }
}
