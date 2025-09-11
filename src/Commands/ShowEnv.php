<?php

namespace AreiaLab\EnvCraft\Commands;

use Illuminate\Console\Command;
use AreiaLab\EnvCraft\Helpers\EnvEditor;

class ShowEnv extends Command
{
    protected $signature = 'env:show {key?}';
    protected $description = 'Show .env values or a specific key';

    public function handle()
    {
        $key = $this->argument('key');
        $all = EnvEditor::readAll();
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
