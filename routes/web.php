<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/doc/{file}', [HomeController::class, 'showDoc']);

// Catch-all pro verze
Route::get('/{version}', function ($version) {
    if (view()->exists("versions.{$version}.index")) {
        
        // Pokud je parametr preview=false, zobrazíme čistou verzi bez wrapperu
        if (request()->query('preview') === 'false') {
            return view("versions.{$version}.index");
        }
        
        // Jinak zobrazíme obalovací layout s device previewerem
        return view('layouts.device-preview', compact('version'));
    }
    abort(404);
})->where('version', 'v[0-9a-z\-]+');
