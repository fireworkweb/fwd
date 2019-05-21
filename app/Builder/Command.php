<?php

namespace App\Builder;

class Command
{
    /** @var string $cwd */
    protected $cwd;

    /** @var array $args */
    protected $args = [];

    /** @var string $command */
    protected $command;

    public function __construct(string $command = '', ...$args)
    {
        $this->setCommand($command);

        foreach ($args as $arg) {
            $this->addArgument($arg);
        }
    }

    public function setCommand(string $command) : Command
    {
        $this->command = $command;

        return $this;
    }

    public function addArgument($argn, $argv = null) : Command
    {
        $this->appendArgument(new Argument($argn, $argv));

        return $this;
    }

    public function appendArgument(Argument $arg) : Command
    {
        $this->args[] = $arg;

        return $this;
    }

    public function getArguments() : array
    {
        return $this->args;
    }

    public function setCwd(string $cwd) : Command
    {
        if ( ! is_dir($cwd)) {
            throw new \InvalidArgumentException('cwd must be an existing directory');
        }

        $this->cwd = $cwd;

        return $this;
    }

    public function getCwd() : string
    {
        return $this->cwd ?: '';
    }

    public function toString() : string
    {
        return $this->__toString();
    }

    public function __toString() : string
    {
        return trim(vsprintf('%s %s', [
            $this->command,
            $this->parseArgumentsToString(),
        ]));
    }

    protected function parseArgumentsToString() : string
    {
        return implode(' ', $this->args);
    }
}
