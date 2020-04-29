<?php

namespace Tests\Feature;

use App\Environment;
use Tests\TestCase;

class ArtTest extends TestCase
{
    public function testArt()
    {
        $this->artisan('art')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec('app php artisan');
    }

    public function testArtMigrateFreshSeed()
    {
        $this->artisan('art migrate:fresh --seed')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec("app php artisan 'migrate:fresh' --seed");
    }

    public function testPhpCustomService()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.php-service');

        $phpService = env('FWD_PHP_SERVICE');

        $this->artisan('art')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec("{$phpService} php artisan");
    }
}
