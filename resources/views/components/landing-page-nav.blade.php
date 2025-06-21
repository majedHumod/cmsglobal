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
                <a href="{{ route('pages.public') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الصفحات</a>
                <a href="{{ route('meal-plans.public') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الوجبات</a>
                <a href="{{ route('faqs.index') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الأسئلة الشائعة</a>
                
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
                        $menuPages = collect([]);
                    }
                @endphp
                
                @foreach($menuPages as $menuPage)
                    <a href="{{ route('pages.show', $menuPage->slug) }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                        {{ $menuPage->access_level_icon }} {{ $menuPage->title }}
                    </a>
                @endforeach
                
                @auth
                    <div class="relative ml-3" x-data="{ open: false }">
                        <div>
                            <button type="button" @click="open = !open" class="flex text-sm bg-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">فتح قائمة المستخدم</span>
                                <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                            </button>
                        </div>
                        
                        <!-- Dropdown menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="origin-top-left absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" 
                             role="menu" 
                             aria-orientation="vertical" 
                             aria-labelledby="user-menu-button" 
                             tabindex="-1"
                             style="display: none;">
                            <div class="px-4 py-2 text-xs text-gray-500">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="font-medium truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">لوحة التحكم</a>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">الملف الشخصي</a>
                            @role('admin')
                            <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">الإعدادات</a>
                            @endrole
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">تسجيل الخروج</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium">إنشاء حساب</a>
                @endauth
            </div>
            
            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                    <span class="sr-only">فتح القائمة الرئيسية</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu, show/hide based on menu state -->
    <div class="md:hidden" x-show="open" x-cloak>
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('pages.public') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الصفحات</a>
            <a href="{{ route('meal-plans.public') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الوجبات</a>
            <a href="{{ route('faqs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الأسئلة الشائعة</a>
            
            @foreach($menuPages as $menuPage)
                <a href="{{ route('pages.show', $menuPage->slug) }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    {{ $menuPage->access_level_icon }} {{ $menuPage->title }}
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