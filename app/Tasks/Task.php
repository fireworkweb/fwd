<?php

namespace App\Tasks;

use App\Commands\Command;
use App\Builder\Command as Builder;

abstract class Task
{
    protected $command;
    protected $quietly = false;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public static function make(Command $command)
    {
        return new static($command);
    }

    abstract public function run(...$args): int;

    public function runCallables(array $commands)
    {
        // Run commands, first that isn't success (0) stops and return that exitCode
        foreach ($commands as $command) {
            $exitCode = is_callable($command)
                ? call_user_func($command)
                : call_user_func_array($command[0], array_get($command, 1, []));

            if ($exitCode) {
                return $exitCode;
            }
        }

        return 0;
    }

    protected function runCallableWaitFor(\Closure $closure, $timeout = 0)
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

            sleep(1);
        }

        return $exitCode;
    }

    public function runQuietly()
    {
        $this->quietly = true;

        $exit = $this->run();

        $this->quietly = false;

        return $exit;
    }

    protected function runCommand(Builder $builder): int
    {
        return $this->quietly
            ? $this->runCommandQuietly($builder)
            : $this->runCommandLoudly($builder);
    }

    protected function runCommandLoudly(Builder $builder): int
    {
        return $this->command->getCommandExecutor()->run($builder);
    }

    protected function runCommandQuietly(Builder $builder): int
    {
        return $this->command->getCommandExecutor()->runQuietly($builder);
    }

    public function runTask(string $title, \Closure $task): int
    {
        return $this->quietly
            ? $task()
            : $this->command->runTask($title, $task);
    }
}
