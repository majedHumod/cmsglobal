<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\SiteSetting::get('site_name', config('app.name', 'Laravel')) }} - @yield('title', 'لوحة التحكم')</title>

    <!-- Favicon -->
    @php
        $siteFavicon = \App\Models\SiteSetting::get('site_favicon');
    @endphp
    @if($siteFavicon)
        <link rel="icon" href="{{ Storage::url($siteFavicon) }}" type="image/x-icon">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700&display=swap" rel="stylesheet" />

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
    
    <style>
        /* RTL Support */
        [dir="rtl"] .ltr\:origin-top-right {
            --tw-origin-x: 0;
        }
        [dir="rtl"] .ltr\:right-0 {
            right: auto;
            left: 0;
        }
        
        /* Custom Styles */
        .sidebar-item.active {
            background-color: rgba(99, 102, 241, 0.1) !important;
            border-left: 3px solid #6366f1 !important;
            color: #6366f1 !important;
        }
        
        .sidebar-item:hover {
            background-color: rgba(99, 102, 241, 0.05);
        }
        
        .sidebar-icon {
            transition: transform 0.2s;
        }
        
        .sidebar-item:hover .sidebar-icon {
            transform: translateX(-4px);
        }
        
        /* Dashboard Cards */
        .dashboard-card {
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Animated Notifications */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .notification-badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation -->
        <nav class="bg-white border-b border-gray-200 fixed z-30 w-full">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-start">
                        <!-- Mobile Sidebar Toggle -->
                        <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="lg:hidden ml-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        
                        <!-- Logo -->
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold flex items-center lg:ml-2.5">
                            @php
                                $siteLogo = \App\Models\SiteSetting::get('site_logo');
                                $siteName = \App\Models\SiteSetting::get('site_name', config('app.name', 'Laravel'));
                            @endphp
                            @if($siteLogo)
                                <img src="{{ Storage::url($siteLogo) }}" class="h-8 ml-2" alt="{{ $siteName }}">
                            @endif
                            <span class="self-center whitespace-nowrap text-indigo-600">{{ $siteName }}</span>
                        </a>
                        
                        <!-- Breadcrumb -->
                        <nav class="flex mb-0 mr-6" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 text-sm font-medium">
                                        الرئيسية
                                    </a>
                                </li>
                                @yield('breadcrumbs')
                            </ol>
                        </nav>
                    </div>
                    
                    <!-- Right Side -->
                    <div class="flex items-center">
                        <!-- Notifications -->
                        <div class="flex items-center">
                            <button type="button" class="p-2 relative text-gray-500 hover:text-gray-900 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="notification-badge bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center absolute -top-1 -right-1">3</span>
                            </button>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="flex items-center mr-3">
                            <div class="relative">
                                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" id="dropdown">
                                    <div class="px-4 py-3">
                                        <p class="text-sm text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <hr>
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الملف الشخصي</a>
                                    <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الإعدادات</a>
                                    <hr>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">تسجيل الخروج</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="flex overflow-hidden pt-16">
            <!-- Sidebar - Moved to right side for RTL -->
            <aside id="sidebar" class="fixed hidden z-20 h-full top-0 right-0 pt-16 lg:flex flex-shrink-0 flex-col w-64 transition-width duration-300" aria-label="Sidebar">
                <div class="relative flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white pt-0">
                    <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                        <div class="flex-1 px-3 bg-white divide-y space-y-1">
                            <!-- Main Navigation -->
                            <ul class="space-y-2 pb-2">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                                        </svg>
                                        <span class="ml-3">لوحة التحكم</span>
                                    </a>
                                </li>
                                
                                <!-- Content Management -->
                                <li>
                                    <a href="{{ route('pages.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('pages.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                                            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path>
                                        </svg>
                                        <span class="ml-3">إدارة الصفحات</span>
                                    </a>
                                </li>
                                
                                @role('admin')
                                <li>
                                    <a href="{{ route('articles.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                                            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path>
                                        </svg>
                                        <span class="ml-3">المقالات</span>
                                    </a>
                                </li>
                                @endrole
                                
                                <li>
                                    <a href="{{ route('notes.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('notes.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-3">الملاحظات</span>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="{{ route('meal-plans.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('meal-plans.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                        </svg>
                                        <span class="ml-3">الجداول الغذائية</span>
                                    </a>
                                </li>
                            </ul>
                            
                            <!-- Admin Section -->
                            @role('admin')
                            <ul class="pt-4 mt-4 space-y-2 border-t border-gray-200">
                                <li>
                                    <a href="{{ route('admin.landing-pages.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('admin.landing-pages.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                        </svg>
                                        <span class="ml-3">الصفحة الرئيسية</span>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="{{ route('admin.faqs.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-3">الأسئلة الشائعة</span>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="{{ route('membership-types.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('membership-types.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-3">إدارة العضويات</span>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="{{ route('admin.permissions.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-3">إدارة الصلاحيات</span>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="{{ route('admin.settings.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-3">إعدادات الموقع</span>
                                    </a>
                                </li>
                                
                                <li>
                                    <button type="button" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 w-full hover:text-indigo-600 group" aria-controls="dropdown-system" data-collapse-toggle="dropdown-system">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="flex-1 ml-3 text-left whitespace-nowrap">إعدادات النظام</span>
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <ul id="dropdown-system" class="hidden py-2 space-y-2">
                                        <li>
                                            <a href="{{ route('admin.settings.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100">الإعدادات العامة</a>
                                        </li>
                                        <li>
                                            <a href="#" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100">النسخ الاحتياطي</a>
                                        </li>
                                        <li>
                                            <a href="#" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100">سجلات النظام</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            @endrole
                            
                            <!-- Help & Support -->
                            <ul class="pt-4 mt-4 space-y-2 border-t border-gray-200">
                                <li>
                                    <a href="#" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-3">المساعدة والدعم</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.settings.index') }}" class="sidebar-item text-base font-normal rounded-lg flex items-center p-2 hover:text-indigo-600 group {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                        <svg class="w-6 h-6 sidebar-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-3">الإعدادات</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Tenant Info -->
                    <div class="hidden absolute bottom-0 right-0 justify-center p-4 space-x-4 w-full lg:flex bg-white border-t border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($siteName) }}&background=6366F1&color=ffffff" alt="Tenant Logo">
                                    <span class="absolute bottom-0 right-0 w-2 h-2 bg-green-400 rounded-full"></span>
                                </div>
                            </div>
                            <div class="mr-3">
                                <p class="text-sm font-medium text-gray-700 truncate">{{ request()->getHost() }}</p>
                                <p class="text-xs font-medium text-gray-500 truncate">نشط</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- Mobile Sidebar Backdrop -->
            <div class="bg-gray-900 opacity-50 hidden fixed inset-0 z-10" id="sidebarBackdrop"></div>
            
            <!-- Main Content -->
            <div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:mr-64">
                <main class="py-10 px-4 sm:px-6 lg:px-8">
                    <!-- Page Header -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('header', 'لوحة التحكم')</h1>
                        @yield('header_actions')
                    </div>
                    
                    <!-- Page Content -->
                    <div class="space-y-6">
                        @if(session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @yield('content')
                    </div>
                </main>
                
                <!-- Footer -->
                <footer class="bg-white p-4 shadow md:flex md:items-center md:justify-between md:p-6 border-t">
                    <span class="text-sm text-gray-500 sm:text-center">{{ \App\Models\SiteSetting::get('footer_text', '© ' . date('Y') . ' ' . $siteName . '. جميع الحقوق محفوظة.') }}
                    </span>
                    <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 sm:mt-0">
                        <li>
                            <a href="#" class="mr-4 hover:underline md:mr-6">حول</a>
                        </li>
                        <li>
                            <a href="#" class="mr-4 hover:underline md:mr-6">سياسة الخصوصية</a>
                        </li>
                        <li>
                            <a href="#" class="hover:underline">اتصل بنا</a>
                        </li>
                    </ul>
                </footer>
            </div>
        </div>
    </div>
    
    @stack('modals')
    @livewireScripts
    
    <script>
        // Toggle Sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebarMobile = document.getElementById('toggleSidebarMobile');
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            
            if (toggleSidebarMobile) {
                toggleSidebarMobile.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                    sidebarBackdrop.classList.toggle('hidden');
                });
            }
            
            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', function() {
                    sidebar.classList.add('hidden');
                    sidebarBackdrop.classList.add('hidden');
                });
            }
            
            // User Dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const dropdown = document.getElementById('dropdown');
            
            if (userMenuButton && dropdown) {
                userMenuButton.addEventListener('click', function() {
                    dropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !dropdown.contains(event.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }
            
            // Collapsible Sidebar Items
            const collapsibleButtons = document.querySelectorAll('[data-collapse-toggle]');
            
            collapsibleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-collapse-toggle');
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.classList.toggle('hidden');
                        
                        // Toggle arrow icon
                        const arrow = this.querySelector('svg:last-child');
                        if (arrow) {
                            arrow.classList.toggle('rotate-180');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>