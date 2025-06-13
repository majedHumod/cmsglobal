<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $landingPage->meta_title ?? $landingPage->title }} - {{ isset($siteSettings['general']['site_name']) ? $siteSettings['general']['site_name'] : config('app.name', 'Laravel') }}</title>
    
    @if($landingPage->meta_description)
        <meta name="description" content="{{ $landingPage->meta_description }}">
    @endif

    <!-- Favicon -->
    @if(isset($siteSettings['general']['site_favicon']) && $siteSettings['general']['site_favicon'])
        <link rel="icon" href="{{ Storage::url($siteSettings['general']['site_favicon']) }}" type="image/x-icon">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Colors -->
    @if(isset($siteSettings['general']['primary_color']) || isset($siteSettings['general']['secondary_color']))
    <style>
        :root {
            --primary-color: {{ $siteSettings['general']['primary_color'] ?? '#6366f1' }};
            --secondary-color: {{ $siteSettings['general']['secondary_color'] ?? '#10b981' }};
        }
        
        .bg-primary {
            background-color: var(--primary-color);
        }
        
        .text-primary {
            color: var(--primary-color);
        }
        
        .border-primary {
            border-color: var(--primary-color);
        }
        
        .bg-secondary {
            background-color: var(--secondary-color);
        }
        
        .text-secondary {
            color: var(--secondary-color);
        }
        
        .border-secondary {
            border-color: var(--secondary-color);
        }
    </style>
    @endif
    
    <!-- Custom Styles for Landing Page -->
    <style>
        .hero-section {
            position: relative;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 0 20px;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 2rem;
            line-height: 1.4;
        }
        
        .join-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 1.125rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .join-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        .content-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 1rem;
        }
        
        .content-section img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.25rem;
            }
            
            .hero-subtitle {
                font-size: 1.25rem;
            }
            
            .hero-section {
                min-height: 450px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Site Header -->
    <x-site-header />
    
    <!-- Hero Section -->
    <section class="hero-section" style="background-image: url('{{ Storage::url($landingPage->header_image) }}');">
        <div class="hero-content">
            <h1 class="hero-title" style="color: {{ $landingPage->header_text_color }};">{{ $landingPage->title }}</h1>
            
            @if($landingPage->subtitle)
                <p class="hero-subtitle" style="color: {{ $landingPage->header_text_color }};">{{ $landingPage->subtitle }}</p>
            @endif
            
            @if($landingPage->show_join_button && $landingPage->join_button_text && $landingPage->join_button_url)
                <a href="{{ $landingPage->join_button_url }}" class="join-button" style="background-color: {{ $landingPage->join_button_color }}; color: white;">
                    {{ $landingPage->join_button_text }}
                </a>
            @endif
        </div>
    </section>
    
    <!-- Content Section -->
    <section class="content-section">
        <div class="prose prose-lg max-w-none">
            {!! $landingPage->content !!}
        </div>
    </section>
    
    <!-- Navigation Links -->
    <section class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">استكشف المزيد</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('pages.public') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <svg class="h-12 w-12 text-indigo-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">الصفحات</h3>
                        <p class="text-gray-600">استكشف صفحات الموقع المختلفة</p>
                    </a>
                    
                    <a href="{{ route('meal-plans.public') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <svg class="h-12 w-12 text-indigo-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">الجداول الغذائية</h3>
                        <p class="text-gray-600">تصفح الوجبات والجداول الغذائية</p>
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <svg class="h-12 w-12 text-indigo-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">لوحة التحكم</h3>
                            <p class="text-gray-600">الذهاب إلى لوحة التحكم الخاصة بك</p>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <svg class="h-12 w-12 text-indigo-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">تسجيل الدخول</h3>
                            <p class="text-gray-600">الدخول إلى حسابك</p>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
    
    <!-- Site Footer -->
    <x-site-footer />
</body>
</html>