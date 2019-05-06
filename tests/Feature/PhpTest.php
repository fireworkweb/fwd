<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhpTest extends TestCase
{
    public function testPhp()
    {
        $this->artisan('php')->assertExitCode(0);

        $this->asFWDUser()->assertDockerComposeExec('app php -v');
    }

    public function testPhpInstall()
    {
        $this->artisan('php -a')->assertExitCode(0);

        $this->asFWDUser()->assertDockerComposeExec('app -a');
    }
}
