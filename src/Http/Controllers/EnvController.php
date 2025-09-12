<?php

namespace AreiaLab\EnvCraft\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AreiaLab\EnvCraft\Helpers\EnvEditor;

class EnvController extends Controller
{
    /**
     * Display the grouped .env variables with backups
     */
    public function index()
    {
        $env = EnvEditor::readAll();
        $backups = EnvEditor::listBackups();

        // Group by prefix (2 or more keys with the same prefix)
        $groups = $this->groupByPrefix($env);

        return view('env-manager::index', compact('groups', 'backups'));
    }

    /**
     * Save edited .env variables
     */
    public function save(Request $request)
    {
        $pairs = $request->input('env', []);

        // Restrict editable keys if configured
        $allowed = config('env-manager.editable_keys', []);
        if (!empty($allowed)) {
            $pairs = array_intersect_key($pairs, array_flip($allowed));
        }

        $errors = EnvEditor::setMultiple($pairs);

        if ($errors) {
            return redirect()->back()->with('error', implode('; ', $errors));
        }

        return redirect()->back()->with('success', 'Saved .env values successfully');
    }

    /**
     * Create a backup of the .env file
     */
    public function backup(Request $request)
    {
        $path = EnvEditor::backup();
        return redirect()->back()->with('success', "Backup created: $path");
    }

    /**
     * Restore .env file from a backup
     */
    public function restore(Request $request)
    {
        $file = $request->input('backup');

        if (!$file) {
            return redirect()->back()->with('error', 'No backup selected');
        }

        $ok = EnvEditor::restore($file);

        if (!$ok) {
            return redirect()->back()->with('error', 'Restore failed');
        }

        return redirect()->back()->with('success', 'Restored .env from backup');
    }

    /**
     * Group env keys by prefix (2 or more keys with same prefix)
     */
    private function groupByPrefix(array $env): array
    {
        $groups = [];
        $prefixCount = [];

        // Count how many times each prefix occurs
        foreach ($env as $key => $value) {
            $parts = explode('_', $key);
            $prefix = $parts[0] ?? 'Other';
            $prefixCount[$prefix] = ($prefixCount[$prefix] ?? 0) + 1;
        }

        // Assign keys to groups
        foreach ($env as $key => $value) {
            $parts = explode('_', $key);
            $prefix = $parts[0] ?? 'Other';

            if ($prefixCount[$prefix] >= 2) {
                $groups[$prefix][$key] = $value;
            } else {
                $groups['Other'][$key] = $value;
            }
        }

        return $groups;
    }
}
