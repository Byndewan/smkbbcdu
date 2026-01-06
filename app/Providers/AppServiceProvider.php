<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('restrictMajor', function ($column = 'major_id') {
            $user = Auth::user();
            if ($user && $user->is_operator && $user->major_id) {
                return $this->where($column, $user->major_id);
            }

            return $this;
        });

        EloquentBuilder::macro('restrictMajor', function ($column = 'major_id') {
            $user = Auth::user();

            if ($user && $user->is_operator && $user->major_id) {
                return $this->where($column, $user->major_id);
            }

            return $this;
        });
    }
}
