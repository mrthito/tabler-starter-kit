<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeWidgetCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:widget {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new widget class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Widget';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/widget.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Widgets';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        $viewName = $this->getViewName($name);
        $stub = str_replace('{{ view }}', $viewName, $stub);

        return $stub;
    }

    /**
     * Get the view name for the widget.
     *
     * @param  string  $name
     * @return string
     */
    protected function getViewName($name)
    {
        $name = str_replace($this->getNamespace($name) . '\\', '', $name);
        return 'widgets.' . Str::kebab(class_basename($name));
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        if (parent::handle() === false) {
            return false;
        }

        // Create the view file if --view option is provided
        $this->createView();

        return true;
    }

    /**
     * Create the view file for the widget.
     *
     * @return void
     */
    protected function createView()
    {
        $name = $this->argument('name');
        $viewName = Str::kebab(class_basename($name));
        $viewPath = resource_path('views/widgets/' . $viewName . '.blade.php');

        if ($this->files->exists($viewPath)) {
            $this->error('View already exists!');
            return;
        }

        $stubPath = $this->resolveStubPath('/stubs/widget-view.stub');
        $stub = file_exists($stubPath)
            ? $this->files->get($stubPath)
            : $this->getDefaultViewStub();

        $this->files->ensureDirectoryExists(dirname($viewPath));
        $this->files->put($viewPath, $stub);

        $this->info('View [resources/views/widgets/' . $viewName . '.blade.php] created successfully.');
    }

    /**
     * Get the default view stub content.
     *
     * @return string
     */
    protected function getDefaultViewStub()
    {
        return <<<'BLADE'
<div>
    <!-- Widget content here -->
</div>
BLADE;
    }
}
