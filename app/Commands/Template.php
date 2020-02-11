<?php

namespace App\Commands;

use Illuminate\Support\Facades\File;

class Template extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'template {file=fwd-template.json}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Process template';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $template = json_decode(File::get($this->environment->getContextFile($this->argument('file'))));
        $output = object_get($template, 'output') ?: '.';

        foreach ($template->builds as $build) {
            $path = sprintf('%s/%s', $output, $build->name);

            if (File::isDirectory($path)) {
                File::deleteDirectory($path);
            }

            File::makeDirectory($path, 0755, true);

            foreach ($build->files as $file) {
                $path = sprintf('%s/%s/%s', $output, $build->name, $file->name);
                $content = view($file->path, (array) $build->data)->render();

                File::put($path, $content);

                $this->info("File [{$path}] generated.");
            }
        }

        $this->info('Templates generated successfully.');
    }
}
