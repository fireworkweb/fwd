<?php

namespace App\Commands;

use App\Builder\PhpQa as PhpQaBuilder;
use App\Commands\Traits\HasDynamicArgs;

class PhpMd extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'phpmd';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run phpmd in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            PhpQaBuilder::make('phpmd', $this->getArgs())
        );
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return sprintf('app/ text %s', implode(',', [
            'phpmd/codesize.xml',
            'phpmd/controversial.xml',
            'phpmd/design.xml',
            'phpmd/naming.xml',
            'unusedcode',
            'phpmd/cleancode.xml',
        ]));
    }
}
