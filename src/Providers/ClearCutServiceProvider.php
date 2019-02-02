<?php

namespace SherifAI\ClearCut\Providers;

use Illuminate\Support\ServiceProvider;
use SherifAI\ClearCut\Commands\MigrationCommand;

class ClearCutServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // The publication files to publish
        $this->publishes([__DIR__ . '/../config/config.php' => config_path('clearcut.php')]);

        // Append the settings
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'clearcut');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }


    /**
     * Register Migrations Command
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->app->singleton('command.clearcut.migration', function ($app) {
            return new MigrationCommand($app);
        });

        $this->commands('command.clearcut.migration');
    }
}
