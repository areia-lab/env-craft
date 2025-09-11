<?php

namespace AreiaLab\EnvCraft\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AreiaLab\EnvCraft\Helpers\EnvEditor;

class EnvController extends Controller
{
    public function index()
    {
        $env = EnvEditor::readAll();
        $backups = EnvEditor::listBackups();
        return view('env-manager::index', compact('env', 'backups'));
    }

    public function save(Request $request)
    {
        $pairs = $request->input('env', []);
        $allowed = config('env-manager.editable_keys', []);
        if (!empty($allowed)) {
            $pairs = array_intersect_key($pairs, array_flip($allowed));
        }
        $errors = EnvEditor::setMultiple($pairs);
        if ($errors) {
            return redirect()->back()->with('error', implode('; ', $errors));
        }
        return redirect()->back()->with('success', 'Saved .env values');
    }

    public function backup(Request $request)
    {
        $path = EnvEditor::backup();
        return redirect()->back()->with('success', "Backup created: $path");
    }

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
}
