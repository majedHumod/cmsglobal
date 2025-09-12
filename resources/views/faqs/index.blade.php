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
<body class="font-sans antialiased pt-16 bg-gray-50">
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
    <main class="pt-8 pb-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">كيف يمكننا مساعدتك؟</h1>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input type="text" placeholder="ابحث هنا..." class="w-full px-6 py-4 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            بحث
                        </button>
                    </div>
                </div>
            </div>

            <!-- Category Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                @php
                    // جلب الفئات الفعلية من قاعدة البيانات
                    try {
                        $actualCategories = \App\Models\Faq::select('category')
                            ->where('is_active', true)
                            ->distinct()
                            ->pluck('category')
                            ->toArray();
                    } catch (\Exception $e) {
                        $actualCategories = ['عام', 'العضويات', 'الدفع', 'الحساب'];
                    }

                    // تعيين الأيقونات والألوان للفئات
                    $categoryIcons = [
                        'عام' => [
                            'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>',
                            'bg_color' => 'bg-gray-100',
                            'text_color' => 'text-gray-600',
                            'description' => 'أسئلة عامة ومعلومات أساسية'
                        ],
                        'العضويات' => [
                            'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>',
                            'bg_color' => 'bg-purple-100',
                            'text_color' => 'text-purple-600',
                            'description' => 'معلومات حول العضويات والاشتراكات'
                        ],
                        'الدفع' => [
                            'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>',
                            'bg_color' => 'bg-blue-100',
                            'text_color' => 'text-blue-600',
                            'description' => 'معلومات حول المدفوعات والفواتير'
                        ],
                        'الحساب' => [
                            'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>',
                            'bg_color' => 'bg-orange-100',
                            'text_color' => 'text-orange-600',
                            'description' => 'إدارة حسابك وإعداداتك الشخصية'
                        ],
                        'المحتوى' => [
                            'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>',
                            'bg_color' => 'bg-indigo-100',
                            'text_color' => 'text-indigo-600',
                            'description' => 'أسئلة حول المحتوى والصفحات'
                        ],
                        'الدعم الفني' => [
                            'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg>',
                            'bg_color' => 'bg-green-100',
                            'text_color' => 'text-green-600',
                            'description' => 'تواصل مع فريق الدعم الفني'
                        ]
                    ];

                    // إنشاء مصفوفة الفئات النهائية
                    $categories = [];
                    foreach ($actualCategories as $category) {
                        $categoryData = $categoryIcons[$category] ?? [
                            'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>',
                            'bg_color' => 'bg-gray-100',
                            'text_color' => 'text-gray-600',
                            'description' => 'أسئلة متنوعة'
                        ];
                        
                        $categories[] = [
                            'title' => $category,
                            'description' => $categoryData['description'],
                            'icon' => $categoryData['icon'],
                            'bg_color' => $categoryData['bg_color'],
                            'text_color' => $categoryData['text_color']
                        ];
                    }
                @endphp
                @foreach($categories as $category)
                    <a href="#{{ Str::slug($category['title']) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer block">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 {{ $category['bg_color'] }} rounded-full flex items-center justify-center mb-4 {{ $category['text_color'] }}">
                                {!! $category['icon'] !!}
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category['title'] }}</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $category['description'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Popular Topics Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">المواضيع الشائعة</h2>
                    <p class="text-gray-600">الأسئلة الأكثر شيوعاً والتي يبحث عنها المستخدمون</p>
                </div>

                @if($faqs->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">لا توجد أسئلة شائعة</h3>
                        <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي أسئلة شائعة بعد.</p>
                    </div>
                @else
                    <!-- FAQ Categories -->
                    @foreach($faqs as $categoryName => $categoryFaqs)
                        <div class="mb-8" id="{{ Str::slug($categoryName) }}">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $categoryName }}</h3>
                            
                            <!-- FAQ Grid for this category -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($categoryFaqs as $faq)
                                    <div class="border border-gray-200 rounded-lg" x-data="{ open: false }">
                                        <button 
                                            @click="open = !open" 
                                            class="w-full text-right px-6 py-4 focus:outline-none hover:bg-gray-50 transition-colors duration-200 flex justify-between items-center"
                                        >
                                            <span class="font-medium text-gray-900 text-sm">{{ $faq->question }}</span>
                                            <svg class="w-5 h-5 text-gray-500 transition-transform duration-300 flex-shrink-0 mr-4" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                        
                                        <div x-show="open" x-collapse class="px-6 pb-4 text-gray-600 text-sm border-t border-gray-100">
                                            <div class="pt-4 prose prose-sm max-w-none text-right">
                                                {!! $faq->answer !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Contact CTA -->
                <div class="mt-12 text-center bg-gray-50 rounded-xl p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لم تجد إجابة لسؤالك؟</h3>
                    <p class="text-gray-600 mb-6">تواصل مع فريق الدعم الفني للحصول على مساعدة شخصية</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @php
                            $contactEmail = \App\Models\SiteSetting::get('contact_email');
                        @endphp
                        <a href="{{ $contactEmail ? 'mailto:' . $contactEmail : '#' }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            تواصل معنا
                        </a>
                        @php
                            $contactWhatsapp = \App\Models\SiteSetting::get('contact_whatsapp');
                        @endphp
                        <a href="{{ $contactWhatsapp ? 'https://wa.me/' . str_replace(['+', ' ', '-'], '', $contactWhatsapp) : '#' }}" target="_blank" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path>
                            </svg>
                            واتساب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
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
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </li>
                        @endif
                        
                        @php
                            $contactEmail = \App\Models\SiteSetting::get('contact_email');
                        @endphp
                        @if($contactEmail)
                            <li class="flex items-center justify-end">
                                <span class="text-gray-500 mr-2">{{ $contactEmail }}</span>
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
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
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
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

    <script>
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[type="text"]');
            const searchButton = document.querySelector('button');
            
            if (searchButton) {
                searchButton.addEventListener('click', function() {
                    const query = searchInput.value.trim();
                    if (query) {
                        // Here you can implement search functionality
                        console.log('Searching for:', query);
                    }
                });
            }
            
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        searchButton.click();
                    }
                });
            }
        });
    </script>
</body>
</html>