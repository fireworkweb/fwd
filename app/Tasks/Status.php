<?php

namespace App\Tasks;

use App\Builder\Docker;
use App\Builder\DockerCompose;
use App\Builder\Escaped;
use RuntimeException;

class Status extends Task
{
    /** @var bool $quite */
    protected $quite = false;

    public function run(...$args): int
    {
        $start = Start::make($this->command);

        $chain = [
            [$start, 'handleNetwork'],
            [$this, 'displayServices'],
        ];

        return $this->runCallables($chain);
    }

    public function quite(bool $quite): self
    {
        $this->quite = $quite;

        return $this;
    }

    public function displayServices(): int
    {
        $lines = $this->getOutputLines(DockerCompose::make('ps', '--services'));

        $services = collect($lines)
            ->sort()
            ->map(function ($service) {
                $info = [];
                $isRunning = $this->isRunning($service, $info);

                return [
                    $service,
                    $isRunning
                        ? '<fg=white;bg=green;options=bold>Running</>'
                        : '<fg=white;bg=red;options=bold>Not running</>',
                    $info['ports'],
                    $info['state'],
                ];
            })
            ->toArray();

        $this->command->table([
            'Service',
            'Status',
            'Ports',
            'State',
        ], $services);

        return 0;
    }

    public function isRunning(string $service, array &$info): bool
    {
        $id = $this->getOutput(DockerCompose::make('ps', '-q', $service));

        $isRunning = ! empty($id);
        $info = [
            'state' => '',
            'ports' => '',
        ];

        if ($isRunning) {
            $ps = $this->getOutput(Docker::make(
                'ps',
                '-a',
                '--filter',
                'ID=' . $id,
                '--format',
                Escaped::make('{{.Status}} | {{.Ports}}')
            ));

            if (empty($ps) || mb_strpos($ps, '|') === false) {
                throw new RuntimeException('could not fetch docker ps status of container with ID: ' . $id);
            }

            [
                $info['state'],
                $info['ports'],
            ] = array_map('trim', explode('|', $ps));
        }

        return $isRunning;
    }
}
