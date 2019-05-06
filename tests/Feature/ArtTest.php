<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArtTest extends TestCase
{
    public function testArt()
    {
        $this->artisan('art')->assertExitCode(0);

        $this->asFWDUser()->assertDockerComposeExec('app php artisan');
    }

    public function testArtMigrateFreshSeed()
    {
        $this->artisan('art migrate:fresh --seed')->assertExitCode(0);

        $this->asFWDUser()->assertDockerComposeExec("app php artisan 'migrate:fresh' --seed");
    }
}
