<?php

namespace Tests\Feature;

use App\Environment;
use Tests\TestCase;

class PhpTest extends TestCase
{
    public function testPhp()
    {
        $this->artisan('php')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec('app php -v');
    }

    public function testPhpInstall()
    {
        $this->artisan('php -a')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec('app php -a');
    }

    public function testPhpCustomService()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.php-service');

        $phpService = env('FWD_PHP_SERVICE');

        $this->artisan('php')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec("{$phpService} php -v");
    }
}
