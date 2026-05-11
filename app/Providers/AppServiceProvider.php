<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $cms = \App\Models\ContentCms::all()->groupBy('section')->map(function ($items) {
                return $items->keyBy('key');
            });
            $view->with('cms', $cms);
        });
    }
}
