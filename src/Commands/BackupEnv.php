<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class BackupEnv extends Command
{
    protected $signature = 'env:backup';
    protected $description = 'Create a timestamped backup of .env';

    public function handle()
    {
        $file = Env::backup();
        $this->info("Backup created: $file");
        return 0;
    }
}
