<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class PhpMnd extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'phpmnd';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run phpmnd in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        return $process->dockerRun(env('FWD_IMAGE_PHP_QA'), 'phpmnd', $this->getArgs());
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return implode(' ', [
            'app/',
            '--ignore-funcs=round,sleep,abort,strpad,number_format',
            '--exclude=tests',
            '--progress',
            '--extensions=default_parameter,-return,argument',
        ]);
    }
}
