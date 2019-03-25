<?php

namespace Tests\Feature;

use Tests\TestCase;

class TestTest extends TestCase
{
    public function testTest()
    {
        $this->artisan('test')->assertExitCode(0);

        $this->assertDockerComposeExec('app ./vendor/bin/phpunit');
    }

    public function testTestWithFilter()
    {
        $this->artisan('test --filter=something')->assertExitCode(0);

        $this->assertDockerComposeExec("app ./vendor/bin/phpunit --filter=something");
    }
}
