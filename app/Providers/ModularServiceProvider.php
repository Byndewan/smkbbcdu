<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ModularServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $modulesPath = app_path('Modules');
        if (!File::exists($modulesPath)) {
            return;
        }

        $modules = array_map('basename', File::directories($modulesPath));

        foreach ($modules as $module) {
            $viewPath = $modulesPath . '/' . $module . '/Resources/views';
            if (File::isDirectory($viewPath)) {
                $this->loadViewsFrom($viewPath, $module);
            }

            $migrationPath = $modulesPath . '/' . $module . '/database/migrations';
            if (File::isDirectory($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }
        }
    }
}
