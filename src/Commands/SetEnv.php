<?php

namespace AreiaLab\EnvCraft\Commands;

use AreiaLab\EnvCraft\Facades\Env;
use Illuminate\Console\Command;

class SetEnv extends Command
{
    protected $signature = 'env:set 
                            {--key= : The .env key to set} 
                            {--value= : The value to assign} 
                            {--s|show : Display the new value after setting}';
    protected $description = 'Set a single .env key (interactive if no key/value provided)';

    public function handle(): int
    {
        // Use option values or ask interactively
        $key = $this->option('key') ?: $this->ask('Enter the .env key you want to set');
        if (empty($key)) {
            $this->error('❌ Key cannot be empty.');
            return 1;
        }

        $value = $this->option('value') ?: $this->ask("Enter the value for $key");

        $errors = Env::setMultiple([$key => $value]);

        if ($errors) {
            foreach ($errors as $e) {
                $this->error("❌ $e");
            }
            return 1;
        }

        $this->info("✅ Successfully set <fg=yellow>$key</> = <fg=cyan>$value</>");

        if ($this->option('show')) {
            $all = Env::readAll();
            $this->line('');
            $this->info("Current value of <fg=yellow>$key</>: <fg=cyan>{$all[$key]}</>");
        }

        return 0;
    }
}
