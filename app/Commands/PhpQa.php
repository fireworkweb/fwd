<?php

namespace App\Commands;

use App\CommandExecutor;
use App\Builder\Argument;
use App\Builder\PhpQa as PhpQaBuilder;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class PhpQa extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'php-qa';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run shell commands in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CommandExecutor $executor)
    {
        return $executor->run(new PhpQaBuilder(
            Argument::raw($this->getArgs())
        ));
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return 'php -v';
    }
}
