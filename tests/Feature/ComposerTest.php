<?php

namespace Tests\Feature;

use Tests\TestCase;

class ComposerTest extends TestCase
{
    public function testComposer()
    {
        $this->artisan('composer')->assertExitCode(0);

        $this->assertDockerComposeExec('app composer');
    }

    public function testComposerInstall()
    {
        $this->artisan('composer install')->assertExitCode(0);

        $this->assertDockerComposeExec("app composer install");
    }
}
