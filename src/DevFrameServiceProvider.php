<?php

namespace DevFrame;

use Illuminate\Support\ServiceProvider;

class DevFrameServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dev-frame');

        $this->loadRoutesFrom(__DIR__ . '/../routes/package.php');

        $this->publishes([
            __DIR__ . '/../dist' => public_path('vendor/dev-frame'),
        ], 'dev-frame-assets');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/dev-frame'),
        ], 'dev-frame-views');
    }
}
