<?php

namespace AreiaLab\EnvCraft;

use Illuminate\Support\ServiceProvider;

class EnvCraftServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/env-manager.php', 'env-manager');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'env-manager');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ShowEnv::class,
                Commands\SetEnv::class,
                Commands\BackupEnv::class,
                Commands\RestoreEnv::class,
                Commands\ListBackups::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/env-manager.php' => config_path('env-manager.php'),
        ], 'craft-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/areia/env-manager'),
        ], 'craft-views');
    }
}
