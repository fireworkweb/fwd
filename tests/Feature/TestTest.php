<?php

namespace Tests\Feature;

use Tests\TestCase;

class TestTest extends TestCase
{
    public function testTest()
    {
        $this->assertDockerComposeExec('app ./vendor/bin/phpunit');

        $this->artisan('test')->assertExitCode(0);
    }

    public function testTestWithFilter()
    {
        $this->assertDockerComposeExec("app ./vendor/bin/phpunit --filter=something");

        $this->artisan('test --filter=something')->assertExitCode(0);
    }
}
