<?php

namespace DevFrame\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dev-frame::welcome', ['versions' => $this->getVersions()]);
    }

    private function getVersions(): array
    {
        $path = resource_path('views/versions');
        if (!File::exists($path)) return [];
        $dirs = File::directories($path);
        $versions = array_map('basename', $dirs);
        rsort($versions);
        return $versions;
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
        $versions = $this->getVersions();

        return view('dev-frame::doc', compact('htmlContent', 'fileName', 'versions'));
    }
}
