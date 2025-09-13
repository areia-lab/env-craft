<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class ListBackups extends Command
{
    protected $signature = 'env:list-backups';
    protected $description = 'List available .env backups';

    public function handle()
    {
        $files = Env::listBackups();
        foreach ($files as $f) {
            $this->line($f . ' (' . date('Y-m-d H:i:s', filemtime($f)) . ')');
        }
        return 0;
    }
}
