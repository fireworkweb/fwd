<?php

namespace App;

use Dotenv\Dotenv;
use Dotenv\Environment\DotenvFactory;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;

class Environment
{
    protected $envVariables;

    protected $keys = [
        'FWD_HTTP_PORT',
        'FWD_MYSQL_PORT',
        'FWD_ASUSER',
        'FWD_COMPOSE_EXEC_FLAGS',
        'FWD_SSH_KEY_PATH',
        'FWD_CONTEXT_PATH',
        'FWD_IMAGE_APP',
        'FWD_IMAGE_NODE',
        'FWD_IMAGE_CACHE',
        'FWD_IMAGE_DATABASE',
        'FWD_IMAGE_NODE_QA',
        'FWD_IMAGE_PHP_QA',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
    ];

    public function __construct(DotenvFactory $dotenvFactory)
    {
        $this->envVariables = $dotenvFactory->create();
    }

    public function getKeys(): array
    {
        return static::$keys;
    }

    public function getValues(): array
    {
        return array_only(getenv(), static::getKeys());
    }

    public function load(): void
    {
        static::loadEnv(static::getContextEnv('.fwd'));
        static::loadEnv(static::getContextEnv());
        static::loadEnv(static::getDefaultFwd());

        static::fixVariables();
    }

    public function getDefaultPath()
    {
        return base_path();
    }

    public function getContextPath()
    {
        return getcwd();
    }

    public function getDefaultDockerCompose()
    {
        return static::getDefaultPath() . '/docker-compose.yml';
    }

    public function getContextDockerCompose()
    {
        return static::getContextPath() . '/docker-compose.yml';
    }

    public function getDefaultFwd()
    {
        return static::getDefaultPath() . '/.fwd';
    }

    public function getContextEnv($env = '.env')
    {
        return sprintf('%s/%s', static::getContextPath(), $env);
    }

    public function loadEnv($envFile, $overload = false): void
    {
        try {
            $method = $overload ? 'overload' : 'safeLoad';

            Dotenv::create(
                pathinfo($envFile, PATHINFO_DIRNAME),
                pathinfo($envFile, PATHINFO_BASENAME)
            )->{$method}();
        } catch (InvalidPathException $e) {
            // nothing to do
        } catch (InvalidFileException $e) {
            echo 'The environment file is invalid: '.$e->getMessage();
            die(1);
        }
    }

    protected function fixVariables(): void
    {
        $this->envVariables->set(
            'FWD_SSH_KEY_PATH',
            str_replace('$HOME', $_SERVER['HOME'], env('FWD_SSH_KEY_PATH'))
        );

        $this->envVariables->set(
            'FWD_CONTEXT_PATH',
            str_replace('$PWD', getcwd(), env('FWD_CONTEXT_PATH'))
        );

        $this->envVariables->set(
            'FWD_ASUSER',
            str_replace('$UID', posix_geteuid(), env('FWD_ASUSER'))
        );
    }
}
