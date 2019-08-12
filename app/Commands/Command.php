<?php

namespace App\Commands;

use App\Environment;
use App\CommandExecutor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LaravelZero\Framework\Commands\Command as BaseCommand;

abstract class Command extends BaseCommand
{
    protected $commandExecutor;
    protected $environment;

    public function runCommands(array $commands)
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

    public function getCommandExecutor()
    {
        return $this->commandExecutor;
    }
}
