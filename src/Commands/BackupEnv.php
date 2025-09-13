<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class BackupEnv extends Command
{
    protected $signature = 'env:backup {--d|details : Show backup directory details}';
    protected $description = 'Create a timestamped backup of the .env file';

    public function handle(): int
    {
        $this->info('Starting .env backup...');

        try {
            $file = Env::backup();

            if ($this->option('details')) {
                $this->line("Backup directory: " . dirname($file));
            }

            $this->newLine();
            $this->line('<fg=green;options=bold>✅ Backup successfully created!</>');
            $this->line("<fg=cyan>$file</>");
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('❌ Backup failed!');
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
