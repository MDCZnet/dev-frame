<?php

use Illuminate\Support\Facades\Route;
use DevFrame\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/doc/{file}', [DashboardController::class, 'showDoc']);

Route::get('/{version}', function (string $version) {
    if (view()->exists("versions.{$version}.index")) {
        if (request()->query('preview') === 'false') {
            return view("versions.{$version}.index");
        }
        return view('dev-frame::layouts.device-preview', compact('version'));
    }
    abort(404);
})->where('version', 'v[0-9a-z\-]+');
