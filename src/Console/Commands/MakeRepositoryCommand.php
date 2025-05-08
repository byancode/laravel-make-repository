<?php

namespace Byancode\Repository\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeRepositoryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name} {--model= : The model that the repository will use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        $model = $this->option('model');
        
        return $this->replaceModel($stub, $model);
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string|null  $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        if (!$model) {
            return str_replace(
                ['use Illuminate\Database\Eloquent\Model;', 'protected $modelClass = Model::class;'],
                ['use Illuminate\Database\Eloquent\Model;', 'protected $modelClass = Model::class;'],
                $stub
            );
        }

        $modelClass = $this->parseModel($model);
        $modelBasename = class_basename($modelClass);

        $replace = [
            'use Illuminate\Database\Eloquent\Model;' => "use Illuminate\Database\Eloquent\Model;\nuse {$modelClass};",
            'protected $modelClass = Model::class;' => "protected \$modelClass = {$modelBasename}::class;",
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            $stub
        );
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new \InvalidArgumentException('Model name contains invalid characters.');
        }

        if (strpos($model, '\\') !== false) {
            return $model;
        }

        $modelNamespace = $this->laravel->getNamespace().'Models\\'.$model;

        if (class_exists($modelNamespace)) {
            return $modelNamespace;
        }

        // If the model doesn't exist in the standard Models namespace,
        // try to find it directly in the app namespace
        $appNamespace = $this->laravel->getNamespace().$model;
        
        if (class_exists($appNamespace)) {
            return $appNamespace;
        }

        return $this->laravel->getNamespace().'Models\\'.$model;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the repository will use'],
        ];
    }
}