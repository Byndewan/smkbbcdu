<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class ModularServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modulesPath = app_path('Modules');
        $modules = array_map('basename', File::directories($modulesPath));

        foreach ($modules as $module) {
            $routePath = $modulesPath . '/' . $module . '/routes';
            if (File::exists($routePath . '/web.php')) {
                Route::middleware('web')
                    ->group($routePath . '/web.php');
            }
            if (File::exists($routePath . '/api.php')) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group($routePath . '/api.php');
            }
            $viewPath = $modulesPath . '/' . $module . '/resources/views';
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
