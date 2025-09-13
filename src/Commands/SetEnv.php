<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class SetEnv extends Command
{
    protected $signature = 'env:set {key} {value}';
    protected $description = 'Set a single .env key';

    public function handle()
    {
        $k = $this->argument('key');
        $v = $this->argument('value');
        $errors = Env::setMultiple([$k => $v]);
        if ($errors) {
            foreach ($errors as $e) $this->error($e);
            return 1;
        }
        $this->info("Set $k=$v");
        return 0;
    }
}
