<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhpCsFixerTest extends TestCase
{
    public function testPhpCsFixer()
    {
        $this->artisan('php-cs-fixer')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:alpine php-cs-fixer fix app --format=txt --dry-run --diff --verbose');
    }

    public function testPhpCsFixerCustom()
    {
        $this->artisan('php-cs-fixer something')->assertExitCode(0);

        $this->assertDockerRun("jakzal/phpqa:alpine php-cs-fixer something");
    }
}
