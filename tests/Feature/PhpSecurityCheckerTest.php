<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhpSecurityCheckerTest extends TestCase
{
    public function testPhpSecurityChecker()
    {
        $this->artisan('php-security-checker')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:1.34-alpine security-checker security:check composer.lock');
    }

    public function testPhpSecurityCheckerCustom()
    {
        $this->artisan('php-security-checker something')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:1.34-alpine security-checker something');
    }
}
