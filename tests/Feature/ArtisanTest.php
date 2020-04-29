<?php

namespace Tests\Feature;

use App\Environment;
use Tests\TestCase;

class ArtisanTest extends TestCase
{
    public function testArtisan()
    {
        $this->artisan('artisan')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec('app php artisan');
    }

    public function testArtisanMigrateFreshSeed()
    {
        $this->artisan('artisan migrate:fresh --seed')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec("app php artisan 'migrate:fresh' --seed");
    }

    public function testPhpCustomService()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.php-service');

        $phpService = env('FWD_PHP_SERVICE');

        $this->artisan('artisan')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec("{$phpService} php artisan");
    }
}
