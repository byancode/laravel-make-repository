<?php

namespace Byancode\Repository;

use Illuminate\Support\ServiceProvider;
use Byancode\Repository\Console\Commands\MakeRepositoryCommand;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRepositoryCommand::class,
            ]);
        }
    }
}