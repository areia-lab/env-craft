<?php

namespace AreiaLab\EnvCraft\Helpers;

class EnvEditor
{
    public static function envPath(): string
    {
        return base_path('.env');
    }
    public static function backupDir(): string
    {
        return storage_path('app/' . config('env.backup.dir_path', 'backup/env-backups'));
    }

    public static function readAll(): array
    {
        $path = static::envPath();
        if (!file_exists($path)) return [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $out = [];
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            if (!strpos($line, '=')) continue;
            [$k, $v] = explode('=', $line, 2);
            $out[trim($k)] = isset($v) ? trim($v) : '';
        }
        return $out;
    }

    public static function setMultiple(array $pairs): array
    {
        $path = static::envPath();
        if (!file_exists($path)) return ['.env not found'];
        $content = file_get_contents($path);
        $errors = [];

        if (config('env.backup.auto_save_when_update')) {
            static::backup();
        }

        foreach ($pairs as $k => $v) {
            if (!preg_match('/^[A-Z0-9_]+$/', $k)) {
                $errors[] = "Invalid key: $k";
                continue;
            }
            $escaped = self::escapeValue($v);
            if (preg_match('/^' . preg_quote($k, '/') . '\s*=.*/m', $content)) {
                $content = preg_replace('/^' . preg_quote($k, '/') . '\s*=.*$/m', "$k=$escaped", $content);
            } else {
                $content .= PHP_EOL . "$k=$escaped";
            }
        }
        if (!empty($errors)) return $errors;
        file_put_contents($path, $content);
        return [];
    }

    public static function escapeValue($value): string
    {
        if ($value === null) return '';
        $value = (string)$value;
        if (preg_match('/\s|#|\$/', $value)) {
            $value = '"' . str_replace('"', '\\"', $value) . '"';
        }
        return $value;
    }

    public static function backup(): string
    {
        $dir = static::backupDir();
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $ts = date('Ymd_His');
        $file = "$dir/.env_backup_$ts";
        copy(static::envPath(), $file);
        $max = (int)config('env.max_backups', 50);
        if ($max > 0) {
            $files = glob($dir . "/.env_backup_*");
            usort($files, fn($a, $b) => filemtime($b) - filemtime($a));
            if (count($files) > $max) {
                foreach (array_slice($files, $max) as $d) @unlink($d);
            }
        }
        return $file;
    }

    public static function listBackups(): array
    {
        $dir = static::backupDir();
        if (!is_dir($dir)) return [];
        $files = glob($dir . "/.env_backup_*");
        usort($files, fn($a, $b) => filemtime($b) - filemtime($a));
        return $files;
    }

    public static function restore(string $backupPath): bool
    {
        if (!file_exists($backupPath)) return false;
        static::backup();
        return copy($backupPath, static::envPath());
    }
}
