<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhpMndTest extends TestCase
{
    public function testPhpMnd()
    {
        $this->artisan('phpmnd')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:1.34-alpine phpmnd app/ --ignore-funcs=round,sleep,abort,strpad,number_format --exclude=tests --progress --extensions=default_parameter,-return,argument');
    }

    public function testPhpMndCustom()
    {
        $this->artisan('phpmnd something')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:1.34-alpine phpmnd something');
    }
}
