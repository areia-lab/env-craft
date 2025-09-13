<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class ShowEnv extends Command
{
    protected $signature = 'env:show {key?}';
    protected $description = 'Show .env values or a specific key';

    public function handle()
    {
        $key = $this->argument('key');
        $all = Env::readAll();
        if ($key) {
            $this->line($all[$key] ?? '');
            return 0;
        }
        foreach ($all as $k => $v) {
            $this->line("$k=$v");
        }
        return 0;
    }
}
