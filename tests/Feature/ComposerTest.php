<?php

namespace Tests\Feature;

use App\Environment;
use Tests\TestCase;

class ComposerTest extends TestCase
{
    public function testComposer()
    {
        $this->artisan('composer')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec('app composer');
    }

    public function testComposerInstall()
    {
        $this->artisan('composer install')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec('app composer install');
    }

    public function testPhpCustomService()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.php-service');

        $phpService = env('FWD_PHP_SERVICE');

        $this->artisan('composer')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec("${phpService} composer");
    }
}
