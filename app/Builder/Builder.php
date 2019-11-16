<?php

namespace App\Builder;

class Builder
{
    /** @var string $cwd */
    protected $cwd;

    /** @var Collection $args */
    protected $args;

    /** @var Builder $wrapper */
    protected $wrapper;

    public function __construct(...$args)
    {
        $this->setWrapper($this->makeWrapper());

        $this->setArgs($this->makeArgs(...$args));
    }

    public static function make(...$args) : self
    {
        return new static(...$args);
    }

    public static function makeWithDefaultArgs(...$args) : self
    {
        $args = array_filter($args) ?: static::getDefaultArgs();

        return new static(...$args);
    }

    public function getProgramName() : string
    {
        return '';
    }

    public function makeArgs(...$args) : array
    {
        return $args;
    }

    public static function getDefaultArgs(): array
    {
        return [];
    }

    public function setArgs(array $args) : self
    {
        $this->args = collect();

        foreach ($args as $arg) {
            $this->addArgument($arg);
        }

        return $this;
    }

    public function addArgument($argn, $argv = null) : self
    {
        $arg = $argn instanceof Argument
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

    public function makeWrapper() : ?self
    {
        return null;
    }

    public function setWrapper(?self $wrapper)
    {
        if ($wrapper) {
            $this->wrapper = $wrapper;
        }

        return $this;
    }

    public function getWrapper() : ?self
    {
        return $this->wrapper;
    }

    protected function build()
    {
        $self = $this->beforeBuild(clone $this);

        if ($wrapper = $self->getWrapper()) {
            $wrapper = clone $wrapper;
            $wrapper->addArgument($self->parseToString());

            return $wrapper;
        }

        return $self;
    }

    protected function beforeBuild(self $command) : self
    {
        return $command;
    }

    public function __toString() : string
    {
        $built = $this->build();

        if ($this->getWrapper()) {
            return (string) $built;
        }

        return $built->parseToString();
    }

    protected function parseToString() : string
    {
        return trim(vsprintf('%s %s', [
            $this->getProgramName(),
            $this->parseArgumentsToString(),
        ]));
    }

    protected function parseArgumentsToString() : string
    {
        return implode(' ', $this->getArguments());
    }

    public function __clone()
    {
        $this->args = clone $this->args;

        if ($this->wrapper) {
            $this->wrapper = clone $this->wrapper;
        }
    }
}
