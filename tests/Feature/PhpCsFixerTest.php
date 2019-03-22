<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhpCsFixerTest extends TestCase
{
    public function testPhpCsFixer()
    {
        $this->assertDockerRun('jakzal/phpqa:alpine php-cs-fixer fix app --format=txt --dry-run --diff --verbose');

        $this->artisan('php-cs-fixer')->assertExitCode(0);
    }

    public function testPhpCsFixerCustom()
    {
        $this->assertDockerRun("jakzal/phpqa:alpine php-cs-fixer something");

        $this->artisan('php-cs-fixer something')->assertExitCode(0);
    }
}
