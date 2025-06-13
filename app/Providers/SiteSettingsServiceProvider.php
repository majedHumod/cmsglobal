<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class SiteSettingsServiceProvider extends ServiceProvider
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
        // Share site settings with all views
        try {
            if (Schema::hasTable('site_settings')) {
                // Get general settings
                $generalSettings = SiteSetting::getGroup('general');
                
                // Get contact settings
                $contactSettings = SiteSetting::getGroup('contact');
                
                // Get social media settings
                $socialSettings = SiteSetting::getGroup('social');
                
                // Get app settings
                $appSettings = SiteSetting::getGroup('app');
                
                // Share with all views
                View::share('siteSettings', [
                    'general' => $generalSettings,
                    'contact' => $contactSettings,
                    'social' => $socialSettings,
                    'app' => $appSettings
                ]);
            }
        } catch (\Exception $e) {
            // If there's an error (like table doesn't exist), just provide empty collections
            View::share('siteSettings', [
                'general' => collect(),
                'contact' => collect(),
                'social' => collect(),
                'app' => collect()
            ]);
        }
    }
}