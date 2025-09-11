<?php

namespace AreiaLab\EnvCraft;

use Illuminate\Support\ServiceProvider;

class EnvCraftServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/env-manager.php' => config_path('env-manager.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'env-manager');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/env-manager'),
        ], 'views');

        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ShowEnv::class,
                Commands\SetEnv::class,
                Commands\BackupEnv::class,
                Commands\RestoreEnv::class,
                Commands\ListBackups::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/env-manager.php', 'env-manager');
    }
}
