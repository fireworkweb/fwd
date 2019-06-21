<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Environment;
use App\Builder\Artisan;

class EnvVarTest extends TestCase
{
    public function testEnvironmentVariableFromFile()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.example');

        $this->assertEquals('custom-var', env('FWD_CUSTOM_ENV_VAR'));
    }

    public function testSafeLoadEnvVariables()
    {
        $env = app(Environment::class);
        $env->set('FWD_CUSTOM_ENV_VAR', 'xxx');

        $env->safeLoadEnv('tests/fixtures/.env.example');

        $this->assertEquals('xxx', env('FWD_CUSTOM_ENV_VAR'));
    }

    public function testOverloadEnvVariables()
    {
        $env = app(Environment::class);
        $env->set('FWD_CUSTOM_ENV_VAR', 'xxx');

        $env->overloadEnv('tests/fixtures/.env.example');

        $this->assertEquals('custom-var', env('FWD_CUSTOM_ENV_VAR'));
    }

    public function testNestingVariablesFromFile()
    {
        $env = app(Environment::class);
        $env->set('CUSTOM_VAR', 'custom-var');

        $env->overloadEnv('tests/fixtures/.env.nesting');

        $dockerBinary = env('FWD_DOCKER_BIN');
        $this->assertEquals("docker binary is: $dockerBinary", env('FWD_CUSTOM_DOCKER_BINARY'));

        $this->assertEquals('--custom-var=custom-var', env('FWD_CUSTOM_VAR_NESTED'));
    }

    public function testNestingVariablesFromFileOntoCommand()
    {
        $env = app(Environment::class);
        // this could come from CLI prefixed var setting
        $env->set('DOMAIN', 'example.com');

        $env->overloadEnv('tests/fixtures/.env.docker-compose_exec-nested');

        $comm = new Artisan('tinker');
        $this->assertStringContainsString('exec -e DOMAIN=example.com', (string) $comm);
    }
}
