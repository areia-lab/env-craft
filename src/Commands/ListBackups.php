<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class ListBackups extends Command
{
    protected $signature = 'env:backup-list';
    protected $description = 'List available .env backups';

    public function handle(): int
    {
        $files = Env::listBackups();

        if (empty($files)) {
            $this->warn('⚠️  No backups found.');
            return 0;
        }

        $this->info('Available .env backups:');
        $this->line('');

        foreach ($files as $index => $file) {
            $timestamp = date('Y-m-d H:i:s', filemtime($file));
            $label = ($index === 0) ? '<fg=green;options=bold>Latest</>' : '';
            $this->line(
                sprintf(
                    '%d. %s %s (%s)',
                    $index + 1,
                    $file,
                    $label,
                    $timestamp
                )
            );
        }

        $this->line('');
        $this->info("Total backups: " . count($files));

        return 0;
    }
}
