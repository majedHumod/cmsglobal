<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900">إعدادات الموقع</h2>
            <p class="mt-1 text-sm text-gray-500">قم بتخصيص إعدادات موقعك من هنا.</p>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <ul class="flex flex-wrap -mb-px" id="settingsTabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-indigo-600 rounded-t-lg text-indigo-600 active" id="general-tab" data-tabs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                        <svg class="w-5 h-5 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                        </svg>
                        عام
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="contact-tab" data-tabs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                        <svg class="w-5 h-5 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                        معلومات الاتصال
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="social-tab" data-tabs-target="#social" type="button" role="tab" aria-controls="social" aria-selected="false">
                        <svg class="w-5 h-5 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                        التواصل الاجتماعي
                    </button>
                </li>
                <li role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="app-tab" data-tabs-target="#app" type="button" role="tab" aria-controls="app" aria-selected="false">
                        <svg class="w-5 h-5 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        إعدادات التطبيق
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div id="settingsTabContent">
            <!-- General Settings -->
            <div class="block" id="general" role="tabpanel" aria-labelledby="general-tab">
                <form action="{{ route('admin.settings.update-general') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Site Name -->
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700">اسم الموقع</label>
                            <input type="text" name="site_name" id="site_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('site_name', \App\Models\SiteSetting::get('site_name', config('app.name'))) }}">
                            <p class="mt-1 text-sm text-gray-500">اسم موقعك الذي سيظهر في العنوان والهيدر.</p>
                        </div>
                        
                        <!-- Site Description -->
                        <div>
                            <label for="site_description" class="block text-sm font-medium text-gray-700">وصف الموقع</label>
                            <input type="text" name="site_description" id="site_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('site_description', \App\Models\SiteSetting::get('site_description')) }}">
                            <p class="mt-1 text-sm text-gray-500">وصف قصير للموقع يظهر في محركات البحث.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Site Logo -->
                        <div>
                            <label for="site_logo" class="block text-sm font-medium text-gray-700">شعار الموقع</label>
                            @if(\App\Models\SiteSetting::get('site_logo'))
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url(\App\Models\SiteSetting::get('site_logo')) }}" alt="Site Logo" class="h-16 object-contain">
                                    <p class="mt-1 text-xs text-gray-500">الشعار الحالي</p>
                                </div>
                            @endif
                            <input type="file" name="site_logo" id="site_logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-sm text-gray-500">يفضل أن يكون الشعار بصيغة PNG أو SVG بخلفية شفافة.</p>
                        </div>
                        
                        <!-- Site Favicon -->
                        <div>
                            <label for="site_favicon" class="block text-sm font-medium text-gray-700">أيقونة التبويب (Favicon)</label>
                            @if(\App\Models\SiteSetting::get('site_favicon'))
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url(\App\Models\SiteSetting::get('site_favicon')) }}" alt="Site Favicon" class="h-8 w-8 object-contain">
                                    <p class="mt-1 text-xs text-gray-500">الأيقونة الحالية</p>
                                </div>
                            @endif
                            <input type="file" name="site_favicon" id="site_favicon" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-sm text-gray-500">يفضل أن تكون الأيقونة مربعة بأبعاد 32×32 أو 64×64 بكسل.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Primary Color -->
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700">اللون الرئيسي</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="color" name="primary_color" id="primary_color" class="h-10 w-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('primary_color', \App\Models\SiteSetting::get('primary_color', '#6366f1')) }}">
                                <input type="text" name="primary_color_text" id="primary_color_text" class="ml-2 flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('primary_color', \App\Models\SiteSetting::get('primary_color', '#6366f1')) }}" readonly>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">اللون الرئيسي للموقع (الأزرار، الروابط، إلخ).</p>
                        </div>
                        
                        <!-- Secondary Color -->
                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700">اللون الثانوي</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="color" name="secondary_color" id="secondary_color" class="h-10 w-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('secondary_color', \App\Models\SiteSetting::get('secondary_color', '#10b981')) }}">
                                <input type="text" name="secondary_color_text" id="secondary_color_text" class="ml-2 flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('secondary_color', \App\Models\SiteSetting::get('secondary_color', '#10b981')) }}" readonly>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">اللون الثانوي للموقع (العناصر المساعدة).</p>
                        </div>
                    </div>
                    
                    <!-- Footer Text -->
                    <div>
                        <label for="footer_text" class="block text-sm font-medium text-gray-700">نص التذييل</label>
                        <textarea name="footer_text" id="footer_text" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('footer_text', \App\Models\SiteSetting::get('footer_text', '© ' . date('Y') . ' ' . config('app.name') . '. جميع الحقوق محفوظة.')) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">النص الذي سيظهر في تذييل الصفحة.</p>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            حفظ الإعدادات العامة
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Contact Settings -->
            <div class="hidden" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <form action="{{ route('admin.settings.update-contact') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contact Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                            <input type="email" name="contact_email" id="contact_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('contact_email', \App\Models\SiteSetting::get('contact_email')) }}">
                            <p class="mt-1 text-sm text-gray-500">البريد الإلكتروني الرئيسي للاتصال.</p>
                        </div>
                        
                        <!-- Contact Phone -->
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">رقم الجوال</label>
                            <input type="text" name="contact_phone" id="contact_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('contact_phone', \App\Models\SiteSetting::get('contact_phone', '+966541221765')) }}" dir="ltr">
                            <p class="mt-1 text-sm text-gray-500">رقم الجوال بالصيغة الدولية (مثال: +966541221765).</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- WhatsApp Number -->
                        <div>
                            <label for="contact_whatsapp" class="block text-sm font-medium text-gray-700">رقم واتساب</label>
                            <input type="text" name="contact_whatsapp" id="contact_whatsapp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('contact_whatsapp', \App\Models\SiteSetting::get('contact_whatsapp')) }}" dir="ltr">
                            <p class="mt-1 text-sm text-gray-500">رقم واتساب بالصيغة الدولية (مثال: +966541221765).</p>
                        </div>
                        
                        <!-- Telegram Username -->
                        <div>
                            <label for="contact_telegram" class="block text-sm font-medium text-gray-700">معرف تليجرام</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">@</span>
                                <input type="text" name="contact_telegram" id="contact_telegram" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('contact_telegram', \App\Models\SiteSetting::get('contact_telegram')) }}" dir="ltr">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">معرف تليجرام بدون علامة @ (مثال: cmsglobal).</p>
                        </div>
                    </div>
                    
                    <!-- Physical Address -->
                    <div>
                        <label for="contact_address" class="block text-sm font-medium text-gray-700">العنوان الفعلي</label>
                        <textarea name="contact_address" id="contact_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('contact_address', \App\Models\SiteSetting::get('contact_address')) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">العنوان الفعلي للشركة أو المؤسسة.</p>
                    </div>
                    
                    <!-- Google Maps Link -->
                    <div>
                        <label for="contact_map_link" class="block text-sm font-medium text-gray-700">رابط خرائط جوجل</label>
                        <input type="url" name="contact_map_link" id="contact_map_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('contact_map_link', \App\Models\SiteSetting::get('contact_map_link')) }}" dir="ltr">
                        <p class="mt-1 text-sm text-gray-500">رابط موقعك على خرائط جوجل.</p>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            حفظ معلومات الاتصال
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Social Media Settings -->
            <div class="hidden" id="social" role="tabpanel" aria-labelledby="social-tab">
                <form action="{{ route('admin.settings.update-social') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Facebook -->
                        <div>
                            <label for="social_facebook" class="block text-sm font-medium text-gray-700">فيسبوك</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                <input type="url" name="social_facebook" id="social_facebook" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('social_facebook', \App\Models\SiteSetting::get('social_facebook')) }}" dir="ltr">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">رابط صفحة الفيسبوك.</p>
                        </div>
                        
                        <!-- Twitter -->
                        <div>
                            <label for="social_twitter" class="block text-sm font-medium text-gray-700">تويتر</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                    </svg>
                                </span>
                                <input type="url" name="social_twitter" id="social_twitter" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('social_twitter', \App\Models\SiteSetting::get('social_twitter')) }}" dir="ltr">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">رابط حساب تويتر.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Instagram -->
                        <div>
                            <label for="social_instagram" class="block text-sm font-medium text-gray-700">انستغرام</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                <input type="url" name="social_instagram" id="social_instagram" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('social_instagram', \App\Models\SiteSetting::get('social_instagram')) }}" dir="ltr">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">رابط حساب انستغرام.</p>
                        </div>
                        
                        <!-- LinkedIn -->
                        <div>
                            <label for="social_linkedin" class="block text-sm font-medium text-gray-700">لينكد إن</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
                                    </svg>
                                </span>
                                <input type="url" name="social_linkedin" id="social_linkedin" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('social_linkedin', \App\Models\SiteSetting::get('social_linkedin')) }}" dir="ltr">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">رابط حساب لينكد إن.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- YouTube -->
                        <div>
                            <label for="social_youtube" class="block text-sm font-medium text-gray-700">يوتيوب</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path>
                                    </svg>
                                </span>
                                <input type="url" name="social_youtube" id="social_youtube" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('social_youtube', \App\Models\SiteSetting::get('social_youtube')) }}" dir="ltr">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">رابط قناة يوتيوب.</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">روابط التطبيقات</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Android App -->
                            <div>
                                <label for="app_android" class="block text-sm font-medium text-gray-700">تطبيق أندرويد</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M17.523 15.34c-.5 0-.909-.409-.909-.909s.409-.909.91-.909.908.409.908.909-.409.909-.909.909m-10.136 0c-.5 0-.91-.409-.91-.909s.41-.909.91-.909.908.409.908.909-.409.909-.909.909m10.91-4.318H5.703l-.909 1.818v6.82h3.182v-2.727h8.182v2.727h3.182v-6.82l-.91-1.818zm-13.863.909l1.33-2.727 1.5-2.727h8.863l1.5 2.727 1.33 2.727h-14.523zm13.863-6.82h-2.272v-.91h2.272v.91zm-4.09 0h-2.273v-.91h2.273v.91z"></path>
                                        </svg>
                                    </span>
                                    <input type="url" name="app_android" id="app_android" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('app_android', \App\Models\SiteSetting::get('app_android')) }}" dir="ltr">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">رابط تطبيق أندرويد على متجر Google Play.</p>
                            </div>
                            
                            <!-- iOS App -->
                            <div>
                                <label for="app_ios" class="block text-sm font-medium text-gray-700">تطبيق iOS</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M16.462 8.293c-.057-5.177 4.243-7.691 4.433-7.812-2.413-3.531-6.172-4.013-7.516-4.069-3.195-.323-6.239 1.877-7.854 1.877-1.633 0-4.14-1.831-6.812-1.781-3.504.05-6.731 2.034-8.535 5.165-3.644 6.319-.943 15.664 2.616 20.787 1.736 2.507 3.804 5.324 6.516 5.221 2.621-.104 3.608-1.693 6.771-1.693 3.144 0 4.043 1.693 6.806 1.638 2.812-.047 4.595-2.553 6.312-5.071 1.989-2.904 2.807-5.717 2.857-5.861-.062-.028-5.482-2.104-5.539-8.348zm-5.184-15.291c1.446-1.751 2.421-4.185 2.155-6.602-2.079.085-4.593 1.38-6.082 3.122-1.336 1.547-2.507 4.023-2.192 6.394 2.317.179 4.682-1.165 6.119-2.914z"></path>
                                        </svg>
                                    </span>
                                    <input type="url" name="app_ios" id="app_ios" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('app_ios', \App\Models\SiteSetting::get('app_ios')) }}" dir="ltr">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">رابط تطبيق iOS على متجر App Store.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            حفظ روابط التواصل
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- App Settings -->
            <div class="hidden" id="app" role="tabpanel" aria-labelledby="app-tab">
                <form action="{{ route('admin.settings.update-app') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Maintenance Mode -->
                        <div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ \App\Models\SiteSetting::get('maintenance_mode') ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="maintenance_mode" class="font-medium text-gray-700">وضع الصيانة</label>
                                    <p class="text-gray-500">تفعيل وضع الصيانة لإغلاق الموقع مؤقتاً.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enable Registration -->
                        <div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="enable_registration" id="enable_registration" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ \App\Models\SiteSetting::get('enable_registration', true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="enable_registration" class="font-medium text-gray-700">تفعيل التسجيل</label>
                                    <p class="text-gray-500">السماح للمستخدمين بإنشاء حسابات جديدة.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Maintenance Message -->
                    <div>
                        <label for="maintenance_message" class="block text-sm font-medium text-gray-700">رسالة الصيانة</label>
                        <textarea name="maintenance_message" id="maintenance_message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('maintenance_message', \App\Models\SiteSetting::get('maintenance_message', 'الموقع قيد الصيانة حالياً. يرجى المحاولة لاحقاً.')) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">الرسالة التي ستظهر للزوار أثناء وضع الصيانة.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Default Locale -->
                        <div>
                            <label for="default_locale" class="block text-sm font-medium text-gray-700">اللغة الافتراضية</label>
                            <select name="default_locale" id="default_locale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="ar" {{ \App\Models\SiteSetting::get('default_locale', 'ar') == 'ar' ? 'selected' : '' }}>العربية</option>
                                <option value="en" {{ \App\Models\SiteSetting::get('default_locale') == 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">اللغة الافتراضية للموقع.</p>
                        </div>
                        
                        <!-- Items Per Page -->
                        <div>
                            <label for="items_per_page" class="block text-sm font-medium text-gray-700">عدد العناصر في الصفحة</label>
                            <input type="number" name="items_per_page" id="items_per_page" min="5" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('items_per_page', \App\Models\SiteSetting::get('items_per_page', 15)) }}">
                            <p class="mt-1 text-sm text-gray-500">عدد العناصر التي تظهر في كل صفحة من قوائم العرض.</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            حفظ إعدادات التطبيق
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tabs functionality
        const tabButtons = document.querySelectorAll('[role="tab"]');
        const tabPanels = document.querySelectorAll('[role="tabpanel"]');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Hide all tab panels
                tabPanels.forEach(panel => {
                    panel.classList.add('hidden');
                });
                
                // Show the selected tab panel
                const panelId = button.getAttribute('data-tabs-target').substring(1);
                document.getElementById(panelId).classList.remove('hidden');
                
                // Update active state for tab buttons
                tabButtons.forEach(btn => {
                    btn.setAttribute('aria-selected', 'false');
                    btn.classList.remove('border-indigo-600', 'text-indigo-600');
                    btn.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                });
                
                button.setAttribute('aria-selected', 'true');
                button.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                button.classList.add('border-indigo-600', 'text-indigo-600');
            });
        });
        
        // Color picker sync
        const primaryColor = document.getElementById('primary_color');
        const primaryColorText = document.getElementById('primary_color_text');
        const secondaryColor = document.getElementById('secondary_color');
        const secondaryColorText = document.getElementById('secondary_color_text');
        
        if (primaryColor && primaryColorText) {
            primaryColor.addEventListener('input', () => {
                primaryColorText.value = primaryColor.value;
            });
            
            primaryColorText.addEventListener('input', () => {
                primaryColor.value = primaryColorText.value;
            });
        }
        
        if (secondaryColor && secondaryColorText) {
            secondaryColor.addEventListener('input', () => {
                secondaryColorText.value = secondaryColor.value;
            });
            
            secondaryColorText.addEventListener('input', () => {
                secondaryColor.value = secondaryColorText.value;
            });
        }
    });
</script>
@endsection