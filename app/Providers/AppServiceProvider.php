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
                    $user = auth()->user();
                    $menuPages = \App\Models\Page::where('show_in_menu', true)
                        ->where('is_published', true)
                        ->where(function($query) use ($user) {
                            // الصفحات العامة
                            $query->where('access_level', 'public');
                            
                            if ($user) {
                                // المستخدمين المسجلين
                                $query->orWhere('access_level', 'authenticated');
                                
                                // المستخدمين العاديين
                                if ($user->hasRole('user')) {
                                    $query->orWhere('access_level', 'user');
                                }
                                
                                // مديري الصفحات
                                if ($user->hasRole('page_manager')) {
                                    $query->orWhere('access_level', 'page_manager');
                                }
                                
                                // المديرين
                                if ($user->hasRole('admin')) {
                                    $query->orWhere('access_level', 'admin');
                                }
                                
                                // العضويات المدفوعة
                                if ($user->membership_type_id) {
                                    $query->orWhere(function($q) use ($user) {
                                        $q->where('access_level', 'membership')
                                          ->whereRaw('JSON_CONTAINS(required_membership_types, ?)', [json_encode($user->membership_type_id)]);
                                    });
                                }
                            }
                        })
                        ->orderBy('menu_order')
                        ->get();
                    $view->with('menuPages', $menuPages);
                }
            } catch (\Exception $e) {
                // If there's an error (like table doesn't exist), just provide empty collection
                $view->with('menuPages', collect());
            }
        });
    }
}