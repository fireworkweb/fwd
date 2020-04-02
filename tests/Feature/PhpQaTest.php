<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhpQaTest extends TestCase
{
    public function testPhpQa()
    {
        $this->artisan('php-qa')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:1.34-alpine php -v');
    }

    public function testPhpQaCustom()
    {
        $this->artisan('php-qa something')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:1.34-alpine something');
    }
}
