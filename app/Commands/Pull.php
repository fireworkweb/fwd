<?php

namespace App\Commands;

use App\Commands\Traits\ArtisanCall;
use LaravelZero\Framework\Commands\Command;
use App\Commands\Traits\HasDynamicArgs;
use App\Commands\Traits\RunTask;
use App\Process;

class Pull extends Command
{
    use ArtisanCall, HasDynamicArgs, RunTask;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'pull';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Pull all containers used by fwd.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $images = [
            'app' => env('FWD_IMAGE_APP'),
            'php-qa' => env('FWD_IMAGE_PHP_QA'),
            'node' => env('FWD_IMAGE_NODE'),
            'node-qa' => env('FWD_IMAGE_NODE_QA'),
            'cache' => env('FWD_IMAGE_CACHE'),
            'database' => env('FWD_IMAGE_DATABASE'),
        ];

        foreach ($images as $name => $image) {
            if ($exitCode = $this->pullDockerImage($process, $name, $image)) {
                return $exitCode;
            }
        }
    }

    protected function pullDockerImage(Process $process, $name, $image)
    {
        return $this->runTask("Pulling image for {$name}", function () use ($process, $image) {
            return $process->dockerNoOutput(...[
                'pull',
                $image,
                $this->getArgs(),
            ]);
        });
    }
}
