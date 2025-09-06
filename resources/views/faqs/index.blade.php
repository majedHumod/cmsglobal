<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>الأسئلة الشائعة - {{ \App\Models\SiteSetting::get('site_name', config('app.name', 'Laravel')) }}</title>

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
<body class="font-sans antialiased pt-16">
    <!-- Header Navigation -->
    <header class="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and Site Name -->
                <div class="flex items-center">
                    @php
                        $siteLogo = \App\Models\SiteSetting::get('site_logo');
                        $siteName = \App\Models\SiteSetting::get('site_name', config('app.name', 'Laravel'));
                    @endphp
                    @if($siteLogo)
                        <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                            <img class="h-8 w-auto" src="{{ Storage::url($siteLogo) }}" alt="{{ $siteName }}">
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                            <span class="text-xl font-bold text-indigo-600">{{ $siteName }}</span>
                        </a>
                    @endif
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الرئيسية</a>
                    <a href="{{ route('faqs.index') }}" class="text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الأسئلة الشائعة</a>

                    @php
                        try {
                            $allMenuPages = \App\Models\Page::where('show_in_menu', true)
                                ->where('is_published', true)
                                ->orderBy('menu_order')
                                ->get();

                            $user = auth()->user();
                            $menuPages = $allMenuPages->filter(function($page) use ($user) {
                                if ($page->access_level === 'public') return true;
                                if (!$user) return false;
                                if ($page->access_level === 'authenticated') return true;
                                if ($page->access_level === 'user' && $user->hasRole('user')) return true;
                                if ($page->access_level === 'page_manager' && $user->hasRole('page_manager')) return true;
                                if ($page->access_level === 'admin' && $user->hasRole('admin')) return true;
                                if ($page->access_level === 'membership' && $user->membership_type_id) {
                                    $requiredTypes = $page->required_membership_types;
                                    if (is_string($requiredTypes)) {
                                        $requiredTypes = json_decode($requiredTypes, true) ?: [];
                                    }
                                    return in_array($user->membership_type_id, $requiredTypes);
                                }
                                return false;
                            });
                        } catch (\Exception $e) {
                            $menuPages = collect([]);
                        }
                    @endphp

                    @foreach($menuPages as $menuPage)
                        <a href="{{ route('pages.show', $menuPage->slug) }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            {{ $menuPage->title }}
                        </a>
                    @endforeach

                    @auth
                        <div class="ms-3 relative" x-data="{ open: false }">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        </button>
                                    @else
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ Auth::user()->name }}
                                                <svg class="ms-2 -me-0.5 size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                            </button>
                                        </span>
                                    @endif
                                </x-slot>
                                <x-slot name="content">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">لوحة التحكم</a>
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الملف الشخصي</a>
                                    @role('admin')
                                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الإعدادات</a>
                                    @endrole
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">تسجيل الخروج</button>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium">إنشاء حساب</a>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                        <span class="sr-only">فتح القائمة الرئيسية</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الرئيسية</a>
                <a href="{{ route('faqs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-600 hover:text-indigo-800 hover:bg-gray-50">الأسئلة الشائعة</a>

                @foreach($menuPages as $menuPage)
                    <a href="{{ route('pages.show', $menuPage->slug) }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        {{ $menuPage->title }}
                    </a>
                @endforeach

                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">لوحة التحكم</a>
                    <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الملف الشخصي</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-right px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">تسجيل الخروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-600 hover:text-indigo-800 hover:bg-gray-50">إنشاء حساب</a>
                @endauth
            </div>
        </div>

        <script>
            // Mobile menu toggle
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                
                if (mobileMenuButton && mobileMenu) {
                    mobileMenuButton.addEventListener('click', function() {
                        mobileMenu.classList.toggle('hidden');
                    });
                }
            });
        </script>
    </header>

    <!-- Page Content -->
    <main class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">الأسئلة الشائعة</h1>
                
                @if($faqs->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">لا توجد أسئلة شائعة</h3>
                        <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي أسئلة شائعة بعد.</p>
                    </div>
                @else
                    <div class="space-y-8" x-data="{ activeCategory: null }">
                        @foreach($faqs as $category => $categoryFaqs)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <button 
                                        @click="activeCategory = activeCategory === '{{ $category }}' ? null : '{{ $category }}'"
                                        class="flex justify-between items-center w-full text-right focus:outline-none"
                                    >
                                        <span>{{ $category }}</span>
                                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-300" :class="{'rotate-180': activeCategory === '{{ $category }}'}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </h2>
                                
                                <div x-show="activeCategory === '{{ $category }}'" x-collapse>
                                    <div class="space-y-4 mt-4">
                                        @foreach($categoryFaqs as $faq)
                                            <div class="bg-white rounded-lg shadow-sm p-4" x-data="{ open: false }">
                                                <button 
                                                    @click="open = !open" 
                                                    class="flex justify-between items-center w-full text-right focus:outline-none"
                                                >
                                                    <h3 class="text-lg font-medium text-gray-900">{{ $faq->question }}</h3>
                                                    <svg class="w-5 h-5 text-gray-500 transition-transform duration-300" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                                
                                                <div x-show="open" x-collapse class="mt-4 text-gray-600 prose max-w-none">
                                                    {!! $faq->answer !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-12 text-center">
                        <p class="text-gray-600 mb-4">لم تجد إجابة لسؤالك؟</p>
                        <a href="#" class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            تواصل معنا
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    @include('layouts.footer')
</body>
</html>
    </div>