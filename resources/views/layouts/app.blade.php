<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\SiteSetting::get('site_name', config('app.name', 'Laravel')) }} - @yield('title', '')</title>

        <!-- Favicon -->
        @php
            $siteFavicon = \App\Models\SiteSetting::get('site_favicon');
        @endphp
        @if($siteFavicon)
            <link rel="icon" href="{{ Storage::url($siteFavicon) }}" type="image/x-icon">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        
        <!-- Custom Colors -->
        @php
            $primaryColor = \App\Models\SiteSetting::get('primary_color', '#6366f1');
            $secondaryColor = \App\Models\SiteSetting::get('secondary_color', '#10b981');
        @endphp
        <style>
            :root {
                --primary-color: {{ $primaryColor }};
                --secondary-color: {{ $secondaryColor }};
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
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            <!-- Site Header with Contact Info and Social Media -->
            @include('layouts.header')
            
            <!-- Navigation Menu -->
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>
            
            <!-- Site Footer with Contact Info and Social Media -->
            @include('layouts.footer')
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>