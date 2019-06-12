<?php

namespace App\Commands;

use App\CommandExecutor;
use App\Builder\PhpQa as PhpQaBuilder;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

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
    public function handle(CommandExecutor $executor)
    {
        return $executor->run(new PhpQaBuilder('phpmd', $this->getArgs()));
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
