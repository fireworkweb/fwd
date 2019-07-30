<?php

namespace App\Commands;

use App\Process;
use ReflectionClass;
use Illuminate\Support\Collection;
use App\Commands\Traits\ArtisanCall;
use Brick\Reflection\ImportResolver;
use Symfony\Component\Finder\Finder;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class Dusk extends Command
{
    use ArtisanCall, HasDynamicArgs;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dusk
                            {--f|files= : List of files to find respective test separated by space}
                            {--d|dir : Test files directory}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run artisan dusk commands inside the Application container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $changedFiles = collect(explode(' ', $this->option('files')))
            ->filter()
            ->map(function ($file) {
                return $this->get_class_from_file($file);
            });

        if ($changedFiles->isEmpty()) {
            return $process->asFWDUser()->dockerComposeExec('app php artisan dusk', $this->getArgs());
        }

        $path = base_path($this->option('dir'));
        $files = Finder::create()
            ->files()
            ->in($path)
            ->name('*.php');

        $filter = collect($files)
            ->map(function ($file) {
                return $this->get_class_from_file($file);
            })
            ->filter(function ($class) use ($changedFiles) {
                return $this->getClassAnnotations($class)
                    ->reduce(function ($carry, $item) use ($changedFiles) {
                        return $carry || $changedFiles->contains($item);
                    });
            })
            ->join('|');

        return $process->asFWDUser()->dockerComposeExec('app php artisan dusk',
            preg_replace('/(?:--files=\'[\w\/.]+\'\s+?)|(?:--dir=\'[\w\/.]+\'(\s+)?)/i', '', $this->getArgs()),
            "--filter='$filter'"
        );
    }

    protected function getClassAnnotations($class): Collection
    {
        $class = new ReflectionClass($class);
        $resolver = new ImportResolver($class);
        $comment_string = $class->getDocComment();

        preg_match_all('#(?:@see\s*)(?P<class>[a-zA-Z0-9, ()_].*)#', $comment_string, $matches, PREG_PATTERN_ORDER);

        return collect($matches['class'])
            ->map(function ($class) use ($resolver) {
                return $resolver->resolve($class);
            });
    }

    protected function get_class_from_file($path_to_file)
    {
        $contents = file_get_contents($path_to_file);
        $namespace = $class = '';
        $getting_namespace = $getting_class = false;

        foreach (token_get_all($contents) as $token) {
            if (is_array($token) && $token[0] == T_NAMESPACE) {
                $getting_namespace = true;
            }

            if (is_array($token) && $token[0] == T_CLASS) {
                $getting_class = true;
            }

            if ($getting_namespace === true) {
                if (is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {
                    $namespace .= $token[1];
                } elseif ($token === ';') {
                    $getting_namespace = false;
                }
            }

            if ($getting_class === true) {
                if (is_array($token) && $token[0] == T_STRING) {
                    $class = $token[1];
                    break;
                }
            }
        }

        return $namespace ? $namespace . '\\' . $class : $class;
    }
}
