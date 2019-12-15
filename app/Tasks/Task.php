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

    public static function make(Command $command)
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
        $microSecondsDelay = (int) env('FWD_ATTEMPTS_DELAY');
        $waitedMicroSeconds = 0;

        while ($exitCode = $closure()) {
            if ($timeout === 0) {
                $this->command->getCommandExecutor()->printOutputBuffer();
                break;
            }

            $waitedMicroSeconds += $microSecondsDelay;

            if ($waitedMicroSeconds / 1000000 > $timeout) {
                $this->command->error('fwd: Timed out waiting the command to finish.');
                $this->command->getCommandExecutor()->printOutputBuffer();

                return $exitCode;
            }

            usleep($microSecondsDelay);
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

    protected function runCommandWithoutOutput(Builder $builder, bool $outputOnError = true) : int
    {
        return $this->command->getCommandExecutor()->runQuietly($builder, $outputOnError);
    }

    public function runTask(string $title, \Closure $task) : int
    {
        return $this->quietly
            ? $task()
            : $this->command->runTask($title, $task);
    }
}
