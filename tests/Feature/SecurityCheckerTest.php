<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityCheckerTest extends TestCase
{
    public function testSecurityChecker()
    {
        $this->artisan('security-checker')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:alpine security-checker security:check composer.lock');
    }

    public function testPhpCpdCustom()
    {
        $this->artisan('security-checker something')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:alpine security-checker something');
    }
}
