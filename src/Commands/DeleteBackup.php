<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class DeleteBackup extends Command
{
    protected $signature = 'env:backup-delete
                            {--file= : The backup file path to delete}
                            {--all : Delete all backups}
                            {--pre= : Delete previous N backups (excluding latest)}';

    protected $description = 'Delete .env backup files (specific, all, or previous N)';

    public function handle(): int
    {
        $backups = Env::listBackups();

        if (empty($backups)) {
            $this->warn('⚠️  No backup files found.');
            return 0;
        }

        // Sort descending by modification time
        usort($backups, fn($a, $b) => filemtime($b) - filemtime($a));

        $file = $this->option('file');
        $deleteAll = $this->option('all');
        $preCount = $this->option('pre');

        if ($deleteAll) {
            if (!$this->confirm("Are you sure you want to delete ALL backups?", false)) {
                $this->info('Operation cancelled.');
                return 0;
            }
            foreach ($backups as $f) @unlink($f);
            $this->info("✅ All backups deleted.");
            return 0;
        }

        if ($preCount !== null) {
            $preCount = (int)$preCount;
            if ($preCount <= 0) {
                $this->warn('⚠️  Invalid number for --pre.');
                return 0;
            }

            // Exclude latest
            $toDelete = array_slice($backups, 1, $preCount);
            if (empty($toDelete)) {
                $this->warn("⚠️  No previous backups to delete.");
                return 0;
            }

            if (!$this->confirm("Are you sure you want to delete the previous $preCount backups?", true)) {
                $this->info('Operation cancelled.');
                return 0;
            }

            foreach ($toDelete as $f) @unlink($f);
            $this->info("✅ Deleted previous $preCount backups.");
            return 0;
        }

        if ($file) {
            if (!file_exists($file)) {
                $this->error("❌ File not found: $file");
                return 1;
            }

            if (!$this->confirm("Are you sure you want to delete <fg=yellow>$file</>?", true)) {
                $this->info('Operation cancelled.');
                return 0;
            }

            if (@unlink($file)) {
                $this->info("✅ Successfully deleted backup: $file");
            } else {
                $this->error("❌ Failed to delete backup: $file");
                return 1;
            }

            return 0;
        }

        // If no option, interactive selection (latest 10)
        $this->info('Select a backup to delete:');
        $choices = [];
        $latest10 = array_slice($backups, 0, 10);
        foreach ($latest10 as $i => $path) {
            $name = basename($path) . ($i === 0 ? ' (latest)' : '');
            $choices[$name] = $path;
        }
        $selected = $this->choice('Choose a backup file', array_keys($choices), 0);
        $file = $choices[$selected];

        if (!$this->confirm("Are you sure you want to delete <fg=yellow>$file</>?", true)) {
            $this->info('Operation cancelled.');
            return 0;
        }

        if (@unlink($file)) {
            $this->info("✅ Successfully deleted backup: $file");
        } else {
            $this->error("❌ Failed to delete backup: $file");
            return 1;
        }

        return 0;
    }
}
