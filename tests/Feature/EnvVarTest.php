<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Environment;
use App\Builder\Artisan;

class EnvVarTest extends TestCase
{
    public function testEnvironmentVariableFromFile()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.custom1');

        $this->assertEquals('custom1', env('CUSTOM'));
    }

    public function testSafeLoadEnvVariables()
    {
        $env = app(Environment::class);

        $env->safeLoadEnv('tests/fixtures/.env.custom1');
        $env->safeLoadEnv('tests/fixtures/.env.custom2');

        $this->assertEquals('custom1', env('CUSTOM'));
    }

    public function testOverloadEnvVariables()
    {
        $env = app(Environment::class);

        $env->overloadEnv('tests/fixtures/.env.custom1');
        $env->overloadEnv('tests/fixtures/.env.custom2');

        $this->assertEquals('custom2', env('CUSTOM'));
    }

    public function testNestingVariablesFromFile()
    {
        $env = app(Environment::class);
        $env->overloadEnv('tests/fixtures/.env.custom1');
        $env->overloadEnv('tests/fixtures/.env.nesting');

        $this->assertEquals('--custom=custom1', env('CUSTOM_NESTED'));
    }

    public function testNestingVariablesFromFileOntoCommand()
    {
        $env = app(Environment::class);
        // this could come from CLI prefixed var setting
        $env->overloadEnv('tests/fixtures/.env.custom1');
        $env->overloadEnv('tests/fixtures/.env.docker-compose_exec-nested');

        $comm = new Artisan('tinker');
        $this->assertStringContainsString('exec -e CUSTOM=custom1', (string) $comm);
    }
}
