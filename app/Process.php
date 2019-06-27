<?php

namespace App;

class Process
{
    protected $commands = [];
    protected $cwd = null;
    protected $asUser = null;
    protected $output = true;

    /** @var resource $outputFile */
    protected $outputFile;

    /** @var string $outputBuffer */
    protected $outputBuffer = '';

    public function setAsUser($user)
    {
        $this->asUser = $user;

        return $this;
    }

    public function asFWDUser()
    {
        return $this->setAsUser(env('FWD_ASUSER'));
    }

    public function enableOutput()
    {
        $this->output = true;

        return $this;
    }

    public function disableOutput()
    {
        $this->output = false;

        return $this;
    }

    public function dockerRun(...$command) : int
    {
        $commandPrefix = [
            'run --rm',
            env('FWD_DOCKER_RUN_FLAGS'),
            '-w /app',
            sprintf('-v %s:/app:cached', env('FWD_CONTEXT_PATH')),
            sprintf('-v %s:/home/developer/.ssh/id_rsa:cached', env('FWD_SSH_KEY_PATH')),
            sprintf('-e ASUSER=%s', env('FWD_ASUSER')),
        ];

        return $this->docker(...array_merge($commandPrefix, $command));
    }

    public function dockerComposeExec(...$command) : int
    {
        $params = [
            'exec',
        ];

        if (! empty($this->asUser)) {
            $params[] = '--user';
            $params[] = $this->asUser;
        }

        $params[] = env('FWD_COMPOSE_EXEC_FLAGS');

        return $this->dockerCompose(...$params, ...$command);
    }

    public function dockerComposeExecNoOutput(...$command) : int
    {
        return $this->execNoOutput(function () use ($command) {
            return $this->dockerComposeExec(...$command);
        });
    }

    public function docker(...$command) : int
    {
        $commandPrefix = [
            env('FWD_DOCKER_BIN', 'docker'),
        ];

        return $this->process(array_merge($commandPrefix, $command));
    }

    public function dockerNoOutput(...$command) : int
    {
        return $this->execNoOutput(function () use ($command) {
            return $this->docker(...$command);
        });
    }

    public function dockerCompose(...$command) : int
    {
        $commandPrefix = [
            env('FWD_DOCKER_COMPOSE_BIN', 'docker-compose'),
            sprintf('-p %s', env('FWD_NAME')),
        ];

        // @TODO: make docker-compose.yml optional
        // $environment = app(Environment::class);
        // if (! File::exists($environment->getContextDockerCompose())) {
        //     $commandPrefix[] = sprintf('-f %s', $environment->getDefaultDockerCompose());
        // }

        return $this->process(array_merge($commandPrefix, $command));
    }

    public function process(array $command, string $cwd = null) : int
    {
        $this->setOutputBuffer('');

        $command = $this->buildCommand($command);

        $this->commands[] = $command;

        if (env('FWD_DEBUG') || env('FWD_VERBOSE')) {
            $this->print($command);
        }

        if (env('FWD_DEBUG')) {
            return 0;
        }

        return $this->run($command, $cwd);
    }

    public function commands()
    {
        return $this->commands;
    }

    public function cwd(string $cwd)
    {
        return $this->cwd = $cwd;

        return $this;
    }

    public function hasCommand($command)
    {
        return array_search($command, $this->commands) !== false;
    }

    public function setOutputBuffer(string $outputBuffer): void
    {
        $this->outputBuffer = $outputBuffer;
    }

    public function getOutputBuffer(): string
    {
        return $this->outputBuffer;
    }

    protected function execNoOutput(\Closure $closure): int
    {
        $this->disableOutput();

        $this->prepareOutputFile();

        $exitCode = $closure();

        $this->setOutputBuffer($this->getOutputFileContents());

        $this->unsetOutputFile();

        $this->enableOutput();

        if ($exitCode) {
            $this->print($this->getOutputBuffer());
        }

        return $exitCode;
    }

    protected function buildCommand(array $command)
    {
        return trim(implode(' ', array_filter($command)));
    }

    protected function run(string $command) : int
    {
        $pipes = [];
        $proc = proc_open(
            $command,
            $this->getDescriptors(),
            $pipes,
            $this->cwd,
            null,
            []
        );

        return proc_close($proc);
    }

    protected function getDescriptors() : array
    {
        if ($this->output || env('FWD_VERBOSE')) {
            return [STDIN, STDOUT, STDERR];
        }

        return [STDIN, $this->outputFile, $this->outputFile];
    }

    protected function print($line)
    {
        if (! empty($line)) {
            echo $line . PHP_EOL;
        }
    }

    protected function prepareOutputFile(): void
    {
        $filename = sprintf('%s/fwd_output_%s', sys_get_temp_dir(), uniqid());
        $this->outputFile = fopen($filename, 'w+') ?: fopen('/dev/null', 'w+');
    }

    protected function unsetOutputFile(): void
    {
        $filename = $this->getOutputFileName();

        fclose($this->outputFile);

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    protected function getOutputFileContents()
    {
        $filename = $this->getOutputFileName();

        if (! file_exists($filename)) {
            return '';
        }

        $output = file_get_contents($filename);

        if ($output === false) {
            return "FWD Error: Unexpected failure trying to read the output file $filename.";
        }

        return trim($output);
    }

    protected function getOutputFileName(): string
    {
        $meta = stream_get_meta_data($this->outputFile);

        return $meta['uri'] ?? '';
    }
}
