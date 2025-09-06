<header class="bg-white shadow-sm">
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
                <a href="{{ route('faqs.index') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الأسئلة الشائعة</a>

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
            <a href="{{ route('faqs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الأسئلة الشائعة</a>

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
</header>
