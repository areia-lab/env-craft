<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class RestoreEnv extends Command
{
    protected $signature = 'env:restore {file}';
    protected $description = 'Restore .env from a backup file path';

    public function handle()
    {
        $file = $this->argument('file');
        if (!file_exists($file)) {
            $this->error('File not found');
            return 1;
        }
        $ok = Env::restore($file);
        if ($ok) $this->info('Restored');
        else $this->error('Restore failed');
        return $ok ? 0 : 1;
    }
}
