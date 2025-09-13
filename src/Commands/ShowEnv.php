<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class ShowEnv extends Command
{
    protected $signature = 'env:show {--k|key= : The specific .env key to show}';
    protected $description = 'Show .env values or a specific key';

    public function handle(): int
    {
        $key = $this->option('key');
        $all = Env::readAll();

        if ($key) {
            if (array_key_exists($key, $all)) {
                $this->info("$key = " . '<fg=cyan>' . $all[$key] . '</>');
            } else {
                $this->warn("⚠️  Key '$key' not found in .env");
            }
            return 0;
        }

        if (empty($all)) {
            $this->warn('⚠️  No environment variables found.');
            return 0;
        }

        $this->info('.env variables:');
        $this->line('');

        foreach ($all as $k => $v) {
            $this->line("<fg=yellow>$k</> = <fg=cyan>$v</>");
        }

        $this->line('');
        $this->info("Total variables: " . count($all));

        return 0;
    }
}
