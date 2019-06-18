<?php

namespace App\Commands;

use App\Builder\Docker;
use App\CommandExecutor;
use App\Commands\Traits\RunTask;
use App\Commands\Traits\ArtisanCall;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

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
     * @var CommandExecutor
     */
    protected $executor;

    public function handle(CommandExecutor $executor)
    {
        $this->executor = $executor->disableOutput();

        $images = [
            'app' => env('FWD_IMAGE_APP'),
            'php-qa' => env('FWD_IMAGE_PHP_QA'),
            'node' => env('FWD_IMAGE_NODE'),
            'node-qa' => env('FWD_IMAGE_NODE_QA'),
            'cache' => env('FWD_IMAGE_CACHE'),
            'database' => env('FWD_IMAGE_DATABASE'),
        ];

        foreach ($images as $name => $image) {
            if ($exitCode = $this->pullDockerImage($name, $image)) {
                return $exitCode;
            }
        }
    }

    protected function pullDockerImage($name, $image)
    {
        return $this->runTask("Pulling image for {$name}", function () use ($image) {
            return $this->executor->run(
                new Docker('pull', $image, $this->getArgs())
            );
        });
    }
}
