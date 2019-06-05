<?php

namespace App\Builder\Concerns;

use App\Builder\Argument;
use App\Builder\Unescaped;

trait HasEnvironmentVariables
{
    /** @var array $environment */
    protected $environment = [];

    public function addEnv($var, $value = null)
    {
        $this->appendEnv(new Argument($var, $value));

        return $this;
    }

    public function appendEnv(Argument $env)
    {
        $this->environment[] = $env;

        return $this;
    }

    protected function parseEnvironmentToArgument() : void
    {
        foreach ($this->environment as $envArg) {
            $this->args->prepend(new Argument('-e', Unescaped::make((string) $envArg), ' '));
        }
    }
}
