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
        
        .membership-card {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;            
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .membership-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .membership-card .p-6 {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .membership-card ul {
            margin-bottom: auto;
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
    <!-- Header Navigation -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and Site Name -->
                <div class="flex items-center">
                    @if(isset($siteSettings['general']['site_logo']) && $siteSettings['general']['site_logo'])
                        <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                            <img class="h-8 w-auto" src="{{ Storage::url($siteSettings['general']['site_logo']) }}" alt="{{ $siteSettings['general']['site_name'] ?? config('app.name') }}">
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                            <span class="text-xl font-bold text-indigo-600">{{ $siteSettings['general']['site_name'] ?? config('app.name') }}</span>
                        </a>
                    @endif
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    @php
                        try {
                            // جلب جميع الصفحات التي تظهر في القائمة والمنشورة
                            $allMenuPages = \App\Models\Page::where('show_in_menu', true)
                                           ->where('is_published', true)
                                           ->orderBy('menu_order')
                                           ->get();
                            
                            // تصفية الصفحات بناءً على صلاحيات المستخدم
                            $user = auth()->user();
                            $menuPages = $allMenuPages->filter(function($page) use ($user) {
                                // الصفحات العامة متاحة للجميع
                                if ($page->access_level === 'public') {
                                    return true;
                                }
                                
                                // إذا لم يكن المستخدم مسجل الدخول
                                if (!$user) {
                                    return false;
                                }
                                
                                // المستخدمين المسجلين
                                if ($page->access_level === 'authenticated') {
                                    return true;
                                }
                                
                                // المستخدمين العاديين
                                if ($page->access_level === 'user' && $user->hasRole('user')) {
                                    return true;
                                }
                                
                                // مديري الصفحات
                                if ($page->access_level === 'page_manager' && $user->hasRole('page_manager')) {
                                    return true;
                                }
                                
                                // المديرين
                                if ($page->access_level === 'admin' && $user->hasRole('admin')) {
                                    return true;
                                }
                                
                                // العضويات المدفوعة
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
                            $menuPages = collect();
                        }
                    @endphp
                    
                    @foreach($menuPages as $menuPage)
                        <a href="{{ route('pages.show', $menuPage->slug) }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            {{ $menuPage->access_level_icon }} {{ $menuPage->title }}
                        </a>
                    @endforeach
                    
                    <a href="{{ route('pages.public') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الصفحات</a>
                    <a href="{{ route('meal-plans.public') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الوجبات</a>
                    
                    @auth
                        <div class="relative ml-3">
                            <div>
                                <button type="button" id="user-menu-button" class="flex text-sm bg-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                                </button>
                            </div>
                            
                            <!-- Dropdown menu -->
                            <div id="user-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <div class="px-4 py-2 text-xs text-gray-500">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="font-medium truncate">{{ Auth::user()->email }}</div>
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">لوحة التحكم</a>
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">الملف الشخصي</a>
                                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">الإعدادات</a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">تسجيل الخروج</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium">إنشاء حساب</a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu, show/hide based on menu state -->
        <div class="hidden md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('pages.public') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الصفحات</a>
                <a href="{{ route('meal-plans.public') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الوجبات</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">لوحة التحكم</a>
                    <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الملف الشخصي</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">تسجيل الخروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-600 hover:text-indigo-800 hover:bg-gray-50">إنشاء حساب</a>
                @endauth
            </div>
        </div>
    </header>
    
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
    
    <!-- Membership Types Section -->
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900">خطط العضوية</h2>
                <p class="mt-4 text-xl text-gray-600">اختر الخطة المناسبة لاحتياجاتك</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
                @php
                    try {
                        $membershipTypes = \App\Models\MembershipType::where('is_active', true)
                            ->where('is_protected', false)
                            ->orderBy('sort_order')
                            ->orderBy('price')
                            ->get();
                    } catch (\Exception $e) {
                        $membershipTypes = collect([]);
                    }
                @endphp
                
                @forelse($membershipTypes as $membershipType)
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm membership-card flex flex-col h-full">
                        <div class="p-6 flex flex-col h-full">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $membershipType->name }}</h3>
                            
                            @if($membershipType->description)
                                <p class="text-gray-600 mb-4">{{ $membershipType->description }}</p>
                            @endif
                            
                            <div class="flex items-baseline mb-6">
                                <span class="text-4xl font-bold text-indigo-600">{{ $membershipType->formatted_price }}</span>
                                <span class="text-gray-500 mr-2">/ {{ $membershipType->duration_text }}</span>
                            </div>

                            @if($membershipType->features && is_array($membershipType->features) && count($membershipType->features) > 0)
                                <ul class="space-y-3 mb-6">
                                    @foreach($membershipType->features as $feature)
                                        <li class="flex items-center">
                                            <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ route('register') }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                اشترك الآن
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-500">لا توجد خطط عضوية متاحة حالياً</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    
    <!-- Navigation Links -->
    <section class="bg-white py-12">
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
    
    <!-- FAQ Section -->
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">الأسئلة الشائعة</h2>
                <p class="mt-4 text-xl text-gray-600">إجابات على الأسئلة الأكثر شيوعاً</p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="space-y-6" x-data="{
                    activeAccordion: null,
                    setActiveAccordion(id) {
                        this.activeAccordion = this.activeAccordion === id ? null : id
                    }
                }">
                    @php
                        try {
                            $faqs = \App\Models\Faq::active()->ordered()->take(6)->get();
                        } catch (\Exception $e) {
                            $faqs = collect([]);
                        }
                    @endphp
                    
                    @forelse($faqs as $index => $faq)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <button 
                                @click="setActiveAccordion({{ $index }})" 
                                class="flex justify-between items-center w-full px-6 py-4 text-lg font-medium text-right text-gray-900 focus:outline-none"
                                :aria-expanded="activeAccordion === {{ $index }}"
                            >
                                <span>{{ $faq->question }}</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform duration-300" :class="{'rotate-180': activeAccordion === {{ $index }}}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div 
                                x-show="activeAccordion === {{ $index }}" 
                                x-collapse
                                class="px-6 pb-4 text-gray-600 prose prose-sm max-w-none"
                            >
                                {!! $faq->answer !!}
                            </div>
                        </div>
                    @empty
                        <!-- Fallback FAQs if no database entries -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <button 
                                @click="setActiveAccordion(1)" 
                                class="flex justify-between items-center w-full px-6 py-4 text-lg font-medium text-right text-gray-900 focus:outline-none"
                                :aria-expanded="activeAccordion === 1"
                            >
                                <span>ما هي مميزات العضوية المدفوعة؟</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform duration-300" :class="{'rotate-180': activeAccordion === 1}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div 
                                x-show="activeAccordion === 1" 
                                x-collapse
                                class="px-6 pb-4 text-gray-600"
                            >
                                <p>العضوية المدفوعة توفر لك مجموعة من المميزات الحصرية مثل الوصول إلى محتوى متميز، وجداول غذائية مخصصة، ودعم فني أولوي، بالإضافة إلى تحديثات منتظمة للمحتوى. يمكنك الاطلاع على تفاصيل كل خطة عضوية لمعرفة المميزات المحددة التي تقدمها.</p>
                        @foreach($menuPages as $menuPage)
                            <a href="{{ route('pages.show', $menuPage->slug) }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                                {{ $menuPage->access_level_icon }} {{ $menuPage->title }}
                            </a>
                        @endforeach
                        
                        <a href="{{ route('pages.public') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الصفحات</a>
                        <a href="{{ route('meal-plans.public') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الوجبات</a>
                        
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <button 
                                @click="setActiveAccordion(2)" 
                                class="flex justify-between items-center w-full px-6 py-4 text-lg font-medium text-right text-gray-900 focus:outline-none"
                                :aria-expanded="activeAccordion === 2"
                            >
                                <span>كيف يمكنني إلغاء اشتراكي؟</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform duration-300" :class="{'rotate-180': activeAccordion === 2}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div 
                                x-show="activeAccordion === 2" 
                                x-collapse
                                class="px-6 pb-4 text-gray-600"
                            >
                                <p>يمكنك إلغاء اشتراكك في أي وقت من خلال الذهاب إلى صفحة "إعدادات الحساب" في لوحة التحكم الخاصة بك، ثم النقر على "إدارة الاشتراك" واختيار "إلغاء الاشتراك". سيظل بإمكانك الاستفادة من مميزات العضوية حتى نهاية فترة الاشتراك الحالية.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <!-- Contact CTA -->
                <div class="mt-10 text-center">
                    <p class="text-gray-600 mb-4">لم تجد إجابة لسؤالك؟</p>
                    <a href="{{ route('faqs.index') }}" class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        عرض جميع الأسئلة الشائعة
                    </a>
                    <a href="#" class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white border-indigo-600 hover:bg-indigo-50 mr-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        تواصل معنا
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Site Footer -->
    <x-site-footer />
    
    <script>
        // Toggle user dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>