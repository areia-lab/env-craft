<?php

namespace AreiaLab\EnvCraft\Http\Controllers;

use AreiaLab\EnvCraft\Traits\HandlesEnvGrouping;
use AreiaLab\EnvCraft\Helpers\EnvEditor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EnvController extends Controller
{
    use HandlesEnvGrouping;

    /**
     * Show the grouped .env variables and available backups.
     */
    public function index(): View
    {
        return view('env-manager::index', [
            'groups'  => $this->groupByPrefix(EnvEditor::readAll()),
            'backups' => EnvEditor::listBackups(),
        ]);
    }

    /**
     * Save updated .env variables.
     */
    public function save(Request $request): RedirectResponse
    {
        $pairs = $request->input('env', []);

        // Apply restrictions if editable keys are configured
        $allowed = config('env.editable_keys', []);
        if (!empty($allowed)) {
            $pairs = array_intersect_key($pairs, array_flip($allowed));
        }

        $errors = EnvEditor::setMultiple($pairs);

        if (!empty($errors)) {
            return back()->with('error', implode('; ', $errors));
        }

        return back()->with('success', 'Saved .env values successfully.');
    }

    /**
     * Create a backup of the .env file.
     */
    public function backup(): RedirectResponse
    {
        $path = EnvEditor::backup();

        return back()->with('success', "Backup created: {$path}");
    }

    /**
     * Restore the .env file from a backup.
     */
    public function restore(Request $request): RedirectResponse
    {
        $file = $request->input('backup');

        if (empty($file)) {
            return back()->with('error', 'No backup selected.');
        }

        if (!EnvEditor::restore($file)) {
            return back()->with('error', 'Restore failed.');
        }

        return back()->with('success', 'Restored .env from backup.');
    }
}
