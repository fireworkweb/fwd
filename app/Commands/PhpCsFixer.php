<?php

namespace App\Commands;

use App\Builder\PhpQa as PhpQaBuilder;
use App\Commands\Traits\HasDynamicArgs;

class PhpCsFixer extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'php-cs-fixer';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run php-cs-fixer in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            PhpQaBuilder::make('php-cs-fixer', $this->getArgs())
        );
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return 'fix app --format=txt --dry-run --diff --verbose';
    }
}
