<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhpCpdTest extends TestCase
{
    public function testPhpCpd()
    {
        $this->artisan('phpcpd')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:alpine phpcpd --fuzzy app/');
    }

    public function testPhpCpdCustom()
    {
        $this->artisan('phpcpd something')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:alpine phpcpd something');
    }
}
