<?php

namespace AreiaLab\EnvCraft\Commands;

use Illuminate\Console\Command;
use AreiaLab\EnvCraft\Helpers\EnvEditor;

class SetEnv extends Command
{
    protected $signature = 'env:set {key} {value}';
    protected $description = 'Set a single .env key';

    public function handle()
    {
        $k = $this->argument('key');
        $v = $this->argument('value');
        $errors = EnvEditor::setMultiple([$k => $v]);
        if ($errors) {
            foreach ($errors as $e) $this->error($e);
            return 1;
        }
        $this->info("Set $k=$v");
        return 0;
    }
}
