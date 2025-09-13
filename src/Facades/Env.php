<?php

namespace AreiaLab\EnvCraft\Facades;

use Illuminate\Support\Facades\Facade;

class Env extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'env-editor';
    }
}
