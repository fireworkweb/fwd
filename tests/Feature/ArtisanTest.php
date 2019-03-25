<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArtisanTest extends TestCase
{
    public function testArtisan()
    {
        $this->artisan('artisan')->assertExitCode(0);

        $this->assertDockerComposeExec('app php artisan');
    }

    public function testArtisanMigrateFreshSeed()
    {
        $this->artisan('artisan migrate:fresh --seed')->assertExitCode(0);

        $this->assertDockerComposeExec("app php artisan 'migrate:fresh' --seed");
    }
}
