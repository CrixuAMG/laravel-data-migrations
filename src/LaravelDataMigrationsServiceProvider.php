<?php

namespace CrixuAMG\LaravelDataMigrations;

use CrixuAMG\LaravelDataMigrations\Console\Commands\DataMigrationMakeCommand;
use Illuminate\Support\ServiceProvider;

class LaravelDataMigrationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerMigrations();

        $this->registerCommands();
    }

    /**
     * Register console commands
     */
    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DataMigrationMakeCommand::class,
            ]);
        }
    }

    /**
     * Register the migrations
     */
    private function registerMigrations()
    {
        if (!class_exists('CreateDataMigrationsTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__ . '/database/migrations/create_data_migrations_table.php.stub' => $this->app->databasePath() . "/migrations/{$timestamp}_create_data_migrations_table.php",
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-data-migrations');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-data-migrations', function () {
            return new LaravelDataMigrations;
        });
    }
}
