<?php

namespace App\Tasks;

use App\Builder\Builder;
use App\Commands\Command;

abstract class Task
{
    /** @var Command $command */
    protected $command;

    /** @var bool $quietly */
    protected $quietly = false;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public static function make(Command $command) : self
    {
        return new static($command);
    }

    abstract public function run(...$args) : int;

    public function runCallables(array $commands) : int
    {
        return $this->command->runCommands($commands);
    }

    protected function runCallableWaitFor(\Closure $closure, $timeout = 0) : int
    {
        $seconds = 0;

        while ($exitCode = $closure()) {
            if ($timeout === 0) {
                break;
            }

            if ($seconds++ > $timeout) {
                $this->command->error('Timed out waiting the command to finish');

                return 1;
            }

            usleep(env('FWD_ATTEMPTS_DELAY'));
        }

        return $exitCode;
    }

    public function runQuietly() : int
    {
        $this->quietly = true;

        $exit = $this->run();

        $this->quietly = false;

        return $exit;
    }

    protected function runCommand(Builder $builder) : int
    {
        return $this->quietly
            ? $this->runCommandWithoutOutput($builder)
            : $this->runCommandWithOutput($builder);
    }

    protected function runCommandWithOutput(Builder $builder) : int
    {
        return $this->command->getCommandExecutor()->run($builder);
    }

    protected function runCommandWithoutOutput(Builder $builder) : int
    {
        return $this->command->getCommandExecutor()->runQuietly($builder);
    }

    public function runTask(string $title, \Closure $task) : int
    {
        return $this->quietly
            ? $task()
            : $this->command->runTask($title, $task);
    }
}
