<?php

namespace AreiaLab\EnvCraft\Commands;

use Illuminate\Console\Command;
use AreiaLab\EnvCraft\Helpers\EnvEditor;

class BackupEnv extends Command
{
    protected $signature = 'env:backup';
    protected $description = 'Create a timestamped backup of .env';

    public function handle()
    {
        $file = EnvEditor::backup();
        $this->info("Backup created: $file");
        return 0;
    }
}
