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

        $this->setArgs(...$args);
    }

    public static function make(string $command = '') : self
    {
        return new static($command);
    }

    public function setCommand(string $command) : self
    {
        $this->command = $command;

        return $this;
    }

    public function setArgs(...$args)
    {
        $this->args = collect();

        foreach ($args as $arg) {
            $this->addArgument($arg);
        }
    }

    public function addArgument($argn, $argv = null) : self
    {
        $arg = is_a($argn, Argument::class)
            ? $argn
            : new Argument($argn, $argv);

        $this->args->push($arg);

        return $this;
    }

    public function prependArgument(Argument $arg) : self
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

    public function setCwd(string $cwd) : self
    {
        if (! is_dir($cwd)) {
            throw new \InvalidArgumentException('cwd must be an existing directory');
        }

        $this->cwd = $cwd;

        return $this;
    }

    public function getCwd() : string
    {
        return $this->cwd ?: '';
    }

    /** @var Command */
    protected $wrapper;

    public function setWrapper(Command $command)
    {
        $this->wrapper = $command;

        return $this;
    }

    protected function build()
    {
        if ($this->wrapper) {
            $wrapper = clone $this->wrapper;

            return $wrapper->addArgument($this->toString());
        }

        return $this;
    }

    public function __toString() : string
    {
        $built = $this->build();

        return $built === $this
            ? $built->toString()
            : (string) $built;
    }

    protected function toString() : string
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
