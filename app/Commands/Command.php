<?php

namespace App\Commands;

use App\CommandExecutor;
use App\Environment;
use Illuminate\Support\Arr;
use LaravelZero\Framework\Commands\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends BaseCommand
{
    /** @var CommandExecutor $commandExecutor */
    protected $commandExecutor;

    /** @var Environment $environment */
    protected $environment;

    public function runCommands(array $commands) : int
    {
        // Run commands, first that isn't success (0) stops and return that exitCode
        foreach ($commands as $command) {
            $exitCode = is_callable($command)
                ? call_user_func($command)
                : call_user_func_array($command[0], Arr::get($command, 1, []));

            if ($exitCode) {
                return $exitCode;
            }
        }

        return 0;
    }

    public function runTask(string $title, \Closure $task): int
    {
        $exitCode = 0;

        $this->task($title, function () use (&$exitCode, $task) {
            return ! ($exitCode = $task());
        });

        return $exitCode;
    }

    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandExecutor = app(CommandExecutor::class);
        $this->environment = app(Environment::class);

        return parent::execute($input, $output);
    }

    public function getCommandExecutor() : CommandExecutor
    {
        return $this->commandExecutor;
    }
}
