<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArtisanTest extends TestCase
{
    public function testArtisan()
    {
        $this->assertDockerComposeExec('app php artisan');

        $this->artisan('artisan')->assertExitCode(0);
    }

    public function testArtisanMigrateFreshSeed()
    {
        $this->assertDockerComposeExec("app php artisan 'migrate:fresh' --seed");

        $this->artisan('artisan migrate:fresh --seed')->assertExitCode(0);
    }
}
