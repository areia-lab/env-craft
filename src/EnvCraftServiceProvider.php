<?php

namespace AreiaLab\EnvCraft;

use Illuminate\Support\ServiceProvider;

class EnvCraftServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge default config so users can override in their app
        $this->mergeConfigFrom(
            __DIR__ . '/../config/env.php',
            'env'
        );
    }

    public function boot(): void
    {
        // Bind EnvEditor to the container
        $this->app->singleton('env-editor', function () {
            return new EnvEditor();
        });

        // Views & Routes
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'env-manager');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Register console commands only in CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ShowEnv::class,
                Commands\SetEnv::class,
                Commands\BackupEnv::class,
                Commands\RestoreEnv::class,
                Commands\ListBackups::class,
                Commands\DeleteBackup::class,
            ]);

            // Publish config
            $this->publishes([
                __DIR__ . '/../config/env.php' => config_path('env.php'),
            ], 'craft-env-config');

            // Publish views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/areia/env-manager'),
            ], 'craft-env-views');
        }
    }
}
