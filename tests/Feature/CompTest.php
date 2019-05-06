<?php

namespace Tests\Feature;

use Tests\TestCase;

class CompTest extends TestCase
{
    public function testComp()
    {
        $this->artisan('comp')->assertExitCode(0);

        $this->asFWDUser()->assertDockerComposeExec('app composer');
    }

    public function testCompInstall()
    {
        $this->artisan('comp install')->assertExitCode(0);

        $this->asFWDUser()->assertDockerComposeExec('app composer install');
    }
}
