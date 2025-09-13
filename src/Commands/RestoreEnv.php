<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class RestoreEnv extends Command
{
    protected $signature = 'env:restore 
                            {file? : The backup file path to restore} 
                            {--s|show : Display .env content after restore}';
    protected $description = 'Restore .env from a backup file (interactive if not provided)';

    public function handle(): int
    {
        $file = $this->argument('file');

        // If no file provided, show interactive selection
        if (!$file) {
            $backups = Env::listBackups();

            if (empty($backups)) {
                $this->warn('⚠️  No backup files found.');
                return 1;
            }

            // Sort descending by modification time
            usort($backups, fn($a, $b) => filemtime($b) - filemtime($a));

            // Limit to 10 latest backups
            $backups = array_slice($backups, 0, 10);

            // Add "(latest)" label for the newest backup
            $choices = [];
            foreach ($backups as $i => $b) {
                $name = basename($b);
                $name .= $i === 0 ? ' (latest)' : '';
                $choices[$name] = $b; // key: display, value: actual path
            }

            $this->info('Select a backup to restore:');
            $displayChoice = $this->choice('Choose a backup file', array_keys($choices), 0);
            $file = $choices[$displayChoice];
        }

        if (!file_exists($file)) {
            $this->error("❌ File not found: $file");
            return 1;
        }

        $ok = Env::restore($file);

        if ($ok) {
            $this->info("✅ Successfully restored .env from: $file");

            if ($this->option('show')) {
                $this->line('');
                $all = Env::readAll();
                foreach ($all as $k => $v) {
                    $this->line("<fg=yellow>$k</> = <fg=cyan>$v</>");
                }
            }
        } else {
            $this->error('❌ Restore failed');
            return 1;
        }

        return 0;
    }
}
