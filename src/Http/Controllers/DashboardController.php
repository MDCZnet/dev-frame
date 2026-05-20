<?php

namespace DevFrame\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $versionsPath = resource_path('views/versions');
        $versions = [];

        if (File::exists($versionsPath)) {
            $directories = File::directories($versionsPath);
            $versions = array_map('basename', $directories);
            rsort($versions);
        }

        return view('dev-frame::welcome', compact('versions'));
    }

    public function showDoc(string $file)
    {
        $allowedFiles = ['README.md', 'DEVLOG.md'];
        $fileName = strtoupper($file) . '.md';

        if (!in_array($fileName, $allowedFiles)) {
            abort(404);
        }

        $path = base_path($fileName);

        if (!File::exists($path)) {
            abort(404);
        }

        $htmlContent = Str::markdown(File::get($path));

        return view('dev-frame::doc', compact('htmlContent', 'fileName'));
    }
}
