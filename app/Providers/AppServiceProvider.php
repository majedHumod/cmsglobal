<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

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
        // Set locale
        App::setLocale(Session::get('locale', config('app.locale')));
        
        // Share menu pages with all views
        View::composer('*', function ($view) {
            try {
                // Only load pages if we're in a tenant context and the pages table exists
                if (class_exists(\App\Models\Page::class)) {
                    $menuPages = \App\Models\Page::inMenu()->published()->get();
                    $view->with('menuPages', $menuPages);
                }
            } catch (\Exception $e) {
                // If there's an error (like table doesn't exist), just provide empty collection
                $view->with('menuPages', collect());
            }
        });
    }
}