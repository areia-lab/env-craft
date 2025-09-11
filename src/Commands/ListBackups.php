<?php

namespace AreiaLab\EnvCraft\Commands;

use Illuminate\Console\Command;
use AreiaLab\EnvCraft\Helpers\EnvEditor;

class ListBackups extends Command
{
    protected $signature = 'env:list-backups';
    protected $description = 'List available .env backups';

    public function handle()
    {
        $files = EnvEditor::listBackups();
        foreach ($files as $f) {
            $this->line($f . ' (' . date('Y-m-d H:i:s', filemtime($f)) . ')');
        }
        return 0;
    }
}
