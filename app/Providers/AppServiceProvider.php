<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
        
        try {
            if (Schema::hasTable('settings')) {
                $globalSettings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
                View::share('globalSettings', $globalSettings);
            }
        } catch (\Exception $e) {
            // Ignore during migrations or initial setup
        }

        // Share header categories to all frontend views
        try {
            if (Schema::hasTable('categories')) {
                View::composer(['frontend.layouts.app', 'welcome'], function ($view) {
                    $view->with('headerCategories', \App\Models\Category::headerNav()->get());
                });
            }
        } catch (\Exception $e) {
            // Ignore during migrations or initial setup
        }
    }
}
