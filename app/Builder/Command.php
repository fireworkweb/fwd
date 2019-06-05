<?php

namespace App\Builder;

class Command
{
    /** @var string $cwd */
    protected $cwd;

    /** @var Collection $args */
    protected $args;

    /** @var string $command */
    protected $command;

    public function __construct(string $command = '', ...$args)
    {
        $this->setCommand($command);

        $this->args = collect();

        foreach ($args as $arg) {
            $this->addArgument($arg);
        }
    }

    public static function make(string $args = '') : Command
    {
        return new static(Unescaped::make($args));
    }

    public function setCommand(string $command) : Command
    {
        $this->command = $command;

        return $this;
    }

    public function addArgument($argn, $argv = null) : Command
    {
        $this->appendArgument(is_a($argn, Argument::class)
            ? $argn
            : new Argument($argn, $argv)
        );

        return $this;
    }

    public function appendArgument(Argument $arg) : Command
    {
        $this->args->push($arg);

        return $this;
    }

    public function prependArgument(Argument $arg) : Command
    {
        $this->args->prepend($arg);

        return $this;
    }

    public function getArguments() : array
    {
        return $this->args->map(function ($arg) {
            return (string) $arg;
        })->filter()->toArray();
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
        return implode(' ', $this->getArguments());
    }
}
