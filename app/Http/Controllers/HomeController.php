<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $versionsPath = resource_path('views/versions');
        $versions = [];

        if (File::exists($versionsPath)) {
            $directories = File::directories($versionsPath);
            $versions = array_map('basename', $directories);
            rsort($versions); // Sort so newest is first
        }

        return view('welcome', compact('versions'));
    }

    public function showDoc($file)
    {
        // Allow only specific files to prevent directory traversal
        $allowedFiles = ['README.md', 'DEVLOG.md'];
        $fileName = strtoupper($file) . '.md';

        if (!in_array($fileName, $allowedFiles)) {
            abort(404);
        }

        $path = base_path($fileName);

        if (!File::exists($path)) {
            abort(404);
        }

        $content = File::get($path);
        // Convert Markdown to HTML
        $htmlContent = Str::markdown($content);

        return view('doc', compact('htmlContent', 'fileName'));
    }
}
