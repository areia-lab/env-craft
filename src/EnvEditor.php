<?php

namespace AreiaLab\EnvCraft;

class EnvEditor
{
    /**
     * Get the path to the .env file.
     */
    public function envPath(): string
    {
        return base_path('.env');
    }

    /**
     * Get the path to the backup directory.
     */
    public function backupDir(): string
    {
        return storage_path('app/' . config('env.backup.dir_path', 'backup/env-backups'));
    }

    /**
     * Read all variables from the .env file.
     *
     * @return array<string, string>
     */
    public function readAll(): array
    {
        $path = $this->envPath();

        if (!file_exists($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $variables = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments or invalid lines
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $variables[trim($key)] = trim($value ?? '');
        }

        return $variables;
    }

    /**
     * Update multiple environment variables in the .env file.
     *
     * @param array<string, string|null> $pairs
     * @return string[] Errors if any
     */
    public function setMultiple(array $pairs): array
    {
        $path = $this->envPath();

        if (!file_exists($path)) {
            return ['.env file not found'];
        }

        $content = file_get_contents($path);
        $errors = [];

        // Auto-backup if enabled
        if (config('env.backup.auto_save_when_update')) {
            $this->backup();
        }

        foreach ($pairs as $key => $value) {
            if (!preg_match('/^[A-Z0-9_]+$/', $key)) {
                $errors[] = "Invalid key: {$key}";
                continue;
            }

            $escaped = $this->escapeValue($value);

            // Replace if exists, otherwise append
            $pattern = '/^' . preg_quote($key, '/') . '\s*=.*$/m';
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "{$key}={$escaped}", $content);
            } else {
                $content .= PHP_EOL . "{$key}={$escaped}";
            }
        }

        if (!empty($errors)) {
            return $errors;
        }

        file_put_contents($path, $content);

        return [];
    }

    /**
     * Escape an env value if needed.
     */
    public function escapeValue($value): string
    {
        if ($value === null) {
            return '';
        }

        $value = (string) $value;

        // Wrap in quotes if it contains spaces, #, or $
        if (preg_match('/\s|#|\$/', $value)) {
            $value = '"' . str_replace('"', '\\"', $value) . '"';
        }

        return $value;
    }

    /**
     * Create a backup of the current .env file.
     *
     * @return string Path to the backup file
     */
    public function backup(): string
    {
        $dir = $this->backupDir();

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $timestamp = date('Ymd_His');
        $file = "{$dir}/.env_backup_{$timestamp}";

        copy($this->envPath(), $file);

        $this->enforceBackupLimit($dir);

        return $file;
    }

    /**
     * List all available backups, newest first.
     *
     * @return string[]
     */
    public function listBackups(): array
    {
        $dir = $this->backupDir();

        if (!is_dir($dir)) {
            return [];
        }

        $files = glob("{$dir}/.env_backup_*") ?: [];

        usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));

        return $files;
    }

    /**
     * Restore the .env file from a given backup.
     */
    public function restore(string $backupPath): bool
    {
        if (!file_exists($backupPath)) {
            return false;
        }

        // Backup current before restoring
        $this->backup();

        return copy($backupPath, $this->envPath());
    }

    /**
     * Enforce the maximum number of backups.
     */
    protected function enforceBackupLimit(string $dir): void
    {
        $max = (int) config('env.backup.max_limit', 50);

        if ($max <= 0) {
            return;
        }

        $files = glob("{$dir}/.env_backup_*") ?: [];
        usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));

        foreach (array_slice($files, $max) as $file) {
            @unlink($file);
        }
    }
}
