<?php

use Illuminate\Support\Facades\Route;
use AreiaLab\EnvCraft\Http\Controllers\EnvController;

Route::prefix(config('env.panel.url_prefix', 'admin/env-manager'))
    ->middleware(config('env.middleware', ['web']))
    ->as('env-manager.')
    ->group(function () {
        // Dashboard
        Route::get('/', [EnvController::class, 'index'])->name('index');

        // Save updated env values
        Route::post('/save', [EnvController::class, 'save'])->name('save');

        // Create a backup
        Route::post('/backup', [EnvController::class, 'backup'])->name('backup');

        // Restore from backup
        Route::post('/restore', [EnvController::class, 'restore'])->name('restore');
    });
