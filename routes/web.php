<?php

use Illuminate\Support\Facades\Route;
use AreiaLab\EnvCraft\Http\Controllers\EnvController;

Route::group([
    'middleware' => config('env.middleware', ['web']),
    'prefix' => config('env.panel.url_prefix', 'admin/env-manager'),
    'as' => 'env-manager.'
], function () {
    Route::get('/', [EnvController::class, 'index'])->name('index');
    Route::post('/save', [EnvController::class, 'save'])->name('save');
    Route::post('/backup', [EnvController::class, 'backup'])->name('backup');
    Route::post('/restore', [EnvController::class, 'restore'])->name('restore');
});
