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
        // Create default settings
        $defaultSettings = [
            'general' => [
                'site_name' => config('app.name', 'Laravel'),
                'site_description' => 'نظام إدارة محتوى متكامل يوفر حلول متقدمة لإدارة المحتوى الرقمي.',
                'site_logo' => null,
                'site_favicon' => null,
                'primary_color' => '#6366f1',
                'secondary_color' => '#10b981',
                'footer_text' => '© ' . date('Y') . ' ' . config('app.name', 'Laravel') . '. جميع الحقوق محفوظة.'
            ],
            'contact' => [
                'contact_email' => 'info@example.com',
                'contact_phone' => '+966541221765',
                'contact_whatsapp' => '+966541221765',
                'contact_telegram' => '@cmsglobal',
                'contact_address' => 'الرياض، المملكة العربية السعودية',
                'contact_map_link' => 'https://maps.google.com/?q=24.7136,46.6753'
            ],
            'social' => [
                'social_facebook' => 'https://facebook.com/cmsglobal',
                'social_twitter' => 'https://twitter.com/cmsglobal',
                'social_instagram' => 'https://instagram.com/cmsglobal',
                'social_linkedin' => 'https://linkedin.com/company/cmsglobal',
                'social_youtube' => 'https://youtube.com/c/cmsglobal'
            ],
            'app' => [
                'app_android' => 'https://play.google.com/store/apps/details?id=com.cmsglobal.app',
                'app_ios' => 'https://apps.apple.com/app/cmsglobal/id123456789',
                'maintenance_mode' => false,
                'maintenance_message' => 'الموقع قيد الصيانة حالياً. يرجى المحاولة لاحقاً.',
                'enable_registration' => true,
                'default_locale' => 'ar',
                'items_per_page' => 15
            ]
        ];

        // Try to load settings from database
        $dbSettings = $defaultSettings;
        try {
            if (Schema::hasTable('site_settings')) {
                // Get settings from database
                $generalSettings = SiteSetting::getGroup('general')->toArray();
                $contactSettings = SiteSetting::getGroup('contact')->toArray();
                $socialSettings = SiteSetting::getGroup('social')->toArray();
                $appSettings = SiteSetting::getGroup('app')->toArray();
                
                // Merge with defaults (so we always have all keys)
                $dbSettings = [
                    'general' => array_merge($defaultSettings['general'], $generalSettings),
                    'contact' => array_merge($defaultSettings['contact'], $contactSettings),
                    'social' => array_merge($defaultSettings['social'], $socialSettings),
                    'app' => array_merge($defaultSettings['app'], $appSettings)
                ];
            }
        } catch (\Exception $e) {
            // If there's an error, we'll use the default settings
            \Log::error('Error loading site settings: ' . $e->getMessage());
        }

        // Share settings with all views
        View::share('siteSettings', $dbSettings);
    }
}