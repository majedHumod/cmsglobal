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
<body class="font-sans antialiased pt-16 bg-gray-100">
    <!-- Header Navigation -->
    <header class="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16" dir="rtl">
                <!-- Logo and Site Name -->
                <div class="flex items-center order-1">
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
                <div class="hidden md:flex md:items-center md:space-x-4 order-2">
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
                <div class="flex items-center md:hidden order-3">
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
    <main class="pt-2 pb-12" dir="rtl">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            الرئيسية
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 md:ml-2 text-sm font-medium text-gray-500">الأسئلة الشائعة</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <article class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <header class="mb-8">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">الأسئلة الشائعة</h1>
                        
                        <div class="flex items-center text-sm text-gray-500 mt-4">
                            <span>إجابات على الأسئلة الأكثر شيوعاً</span>
                        </div>
                    </header>
                
                    <div class="prose prose-lg max-w-none text-right" dir="rtl">
                        @if($faqs->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">لا توجد أسئلة شائعة</h3>
                                <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي أسئلة شائعة بعد.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" x-data="{ activeCategory: Object.keys({{ json_encode($faqs->keys()) }})[0] || null }">
                                <!-- Sidebar with Categories -->
                                <div class="lg:col-span-1">
                                    <div class="bg-white rounded-lg shadow-sm p-4 sticky top-20">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">الأقسام</h3>
                                        <nav class="space-y-2">
                                            @foreach($faqs as $category => $categoryFaqs)
                                                @php
                                                    $categoryIcons = [
                                                        'عام' => [
                                                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>',
                                                            'color' => 'orange',
                                                            'description' => 'ابدأ من هنا مع الأساسيات'
                                                        ],
                                                        'العضويات' => [
                                                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>',
                                                            'color' => 'blue',
                                                            'description' => 'تعرف على خطط العضوية المتاحة'
                                                        ],
                                                        'الدفع' => [
                                                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>',
                                                            'color' => 'green',
                                                            'description' => 'معلومات حول المدفوعات والفواتير'
                                                        ],
                                                        'الحساب' => [
                                                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>',
                                                            'color' => 'purple',
                                                            'description' => 'إدارة حسابك وإعداداتك الشخصية'
                                                        ],
                                                        'المحتوى' => [
                                                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>',
                                                            'color' => 'indigo',
                                                            'description' => 'أسئلة حول المحتوى والصفحات'
                                                        ],
                                                        'الدعم الفني' => [
                                                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>',
                                                            'color' => 'gray',
                                                            'description' => 'مساعدة تقنية ودعم فني'
                                                        ]
                                                    ];
                                                    $categoryData = $categoryIcons[$category] ?? [
                                                        'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>',
                                                        'color' => 'gray',
                                                        'description' => 'أسئلة متنوعة'
                                                    ];
                                                @endphp
                                                <button 
                                                    @click="activeCategory = '{{ $category }}'"
                                                    :class="activeCategory === '{{ $category }}' ? 'bg-{{ $categoryData['color'] }}-50 text-{{ $categoryData['color'] }}-700 border-{{ $categoryData['color'] }}-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                                                    class="w-full text-right px-4 py-3 rounded-lg border border-transparent transition-colors duration-200 flex items-center shadow-sm hover:shadow-md"
                                                >
                                                    <div class="flex items-center flex-1">
                                                        <div class="w-10 h-10 rounded-lg bg-{{ $categoryData['color'] }}-100 flex items-center justify-center ml-3 text-{{ $categoryData['color'] }}-600">
                                                            {!! $categoryData['svg'] ?? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>' !!}
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="font-semibold text-sm">{{ $category }}</div>
                                                            <div class="text-xs text-gray-500 mt-0.5">{{ $categoryData['description'] ?? 'أسئلة متنوعة' }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-medium text-{{ $categoryData['color'] }}-600 bg-{{ $categoryData['color'] }}-100 rounded-full">
                                                            {{ $categoryFaqs->count() }}
                                                        </span>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </nav>
                                    </div>
                                </div>
                                
                                <!-- FAQ Content -->
                                <div class="lg:col-span-3">
                                    @foreach($faqs as $category => $categoryFaqs)
                                        <div x-show="activeCategory === '{{ $category }}'" class="space-y-3">
                                            @php
                                                $categoryData = $categoryIcons[$category] ?? ['icon' => '❓', 'color' => 'gray'];
                                            @endphp
                                            <div class="mb-4">
                                                <div class="flex items-center mb-3">
                                                    <div class="w-12 h-12 rounded-xl bg-{{ $categoryData['color'] }}-100 flex items-center justify-center ml-4 text-{{ $categoryData['color'] }}-600">
                                                        {!! $categoryData['svg'] !!}
                                                    </div>
                                                    <div>
                                                        <h2 class="text-2xl font-bold text-gray-900">{{ $category }}</h2>
                                                        <p class="text-sm text-gray-600">{{ $categoryData['description'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-0">
                                                @foreach($categoryFaqs as $faq)
                                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-1" x-data="{ open: false }">
                                                        <button 
                                                            @click="open = !open" 
                                                            class="flex justify-between items-center w-full text-right focus:outline-none px-4 py-1.5 hover:bg-gray-50 transition-colors duration-200"
                                                        >
                                                            <h3 class="text-sm font-medium text-gray-900 pr-4 leading-tight">{{ $faq->question }}</h3>
                                                            <svg class="w-5 h-5 text-gray-500 transition-transform duration-300 flex-shrink-0" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <div x-show="open" x-collapse class="px-4 pt-1 pb-2 text-gray-600 prose prose-sm max-w-none text-right border-t border-gray-100">
                                                            {!! $faq->answer !!}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
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
            </article>
        </div>
    </main>
    
    <!-- Footer -->
    <!-- Site Footer with RTL Support -->
    <footer class="bg-white border-t border-gray-200" dir="rtl">
        <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo and About -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center">
                        @php
                            $siteLogo = \App\Models\SiteSetting::get('site_logo');
                            $siteName = \App\Models\SiteSetting::get('site_name', config('app.name', 'Laravel'));
                        @endphp
                        @if($siteLogo)
                            <img class="h-10 w-auto" src="{{ Storage::url($siteLogo) }}" alt="{{ $siteName }}">
                        @else
                            <span class="text-xl font-bold text-indigo-600">{{ $siteName }}</span>
                        @endif
                    </div>
                    <p class="mt-4 text-gray-500 text-sm text-right">
                        {{ \App\Models\SiteSetting::get('site_description', 'نظام إدارة محتوى متكامل يوفر حلول متقدمة لإدارة المحتوى الرقمي.') }}
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div class="col-span-1">
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase text-right">روابط سريعة</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="{{ route('home') }}" class="text-base text-gray-500 hover:text-indigo-600 text-right block">
                                الرئيسية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.public') }}" class="text-base text-gray-500 hover:text-indigo-600 text-right block">
                                الصفحات
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('meal-plans.public') }}" class="text-base text-gray-500 hover:text-indigo-600 text-right block">
                                الجداول الغذائية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('faqs.index') }}" class="text-base text-gray-500 hover:text-indigo-600 text-right block">
                                الأسئلة الشائعة
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-span-1">
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase text-right">معلومات الاتصال</h3>
                    <ul class="mt-4 space-y-4">
                        @php
                            $contactPhone = \App\Models\SiteSetting::get('contact_phone');
                        @endphp
                        @if($contactPhone)
                            <li class="flex items-center justify-end">
                                <span class="text-gray-500 mr-2">{{ $contactPhone }}</span>
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </li>
                        @endif
                        
                        @php
                            $contactWhatsapp = \App\Models\SiteSetting::get('contact_whatsapp');
                        @endphp
                        @if($contactWhatsapp)
                            <li class="flex items-center justify-end">
                                <span class="text-gray-500 mr-2">{{ $contactWhatsapp }}</span>
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path>
                                </svg>
                            </li>
                        @endif
                        
                        @php
                            $contactEmail = \App\Models\SiteSetting::get('contact_email');
                        @endphp
                        @if($contactEmail)
                            <li class="flex items-center justify-end">
                                <span class="text-gray-500 mr-2">{{ $contactEmail }}</span>
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </li>
                        @endif
                        
                        @php
                            $contactAddress = \App\Models\SiteSetting::get('contact_address');
                        @endphp
                        @if($contactAddress)
                            <li class="flex items-center justify-end">
                                <span class="text-gray-500 mr-2">{{ $contactAddress }}</span>
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </li>
                        @endif
                    </ul>
                </div>
                
                <!-- Social Media -->
                <div class="col-span-1">
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase text-right">تابعنا</h3>
                    <div class="mt-4 flex justify-end space-x-6 space-x-reverse">
                        @php
                            $socialFacebook = \App\Models\SiteSetting::get('social_facebook');
                        @endphp
                        @if($socialFacebook)
                            <a href="{{ $socialFacebook }}" target="_blank" class="text-gray-400 hover:text-indigo-600">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        @endif
                        
                        @php
                            $socialTwitter = \App\Models\SiteSetting::get('social_twitter');
                        @endphp
                        @if($socialTwitter)
                            <a href="{{ $socialTwitter }}" target="_blank" class="text-gray-400 hover:text-indigo-600">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                                </svg>
                            </a>
                        @endif
                        
                        @php
                            $socialInstagram = \App\Models\SiteSetting::get('social_instagram');
                        @endphp
                        @if($socialInstagram)
                            <a href="{{ $socialInstagram }}" target="_blank" class="text-gray-400 hover:text-indigo-600">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        @endif
                        
                        @php
                            $socialYoutube = \App\Models\SiteSetting::get('social_youtube');
                        @endphp
                        @if($socialYoutube)
                            <a href="{{ $socialYoutube }}" target="_blank" class="text-gray-400 hover:text-indigo-600">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="mt-12 border-t border-gray-200 pt-8">
                <p class="text-base text-gray-400 text-center">
                    {{ \App\Models\SiteSetting::get('footer_text', '© ' . date('Y') . ' ' . $siteName . '. جميع الحقوق محفوظة.') }}
                </p>
            </div>
        </div>
    </footer>
</body>
</html>