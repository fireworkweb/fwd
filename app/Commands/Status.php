<?php

namespace App\Commands;

use App\Tasks\Status as TaskStatus;

class Status extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'status';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Shows the status of fwd environment containers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $task = TaskStatus::make($this);

        return $task->run();
    }
}
