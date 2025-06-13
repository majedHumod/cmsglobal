@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('header', 'لوحة التحكم')

@section('header_actions')
<div class="flex items-center space-x-2">
    <a href="{{ route('pages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        إنشاء صفحة جديدة
    </a>
</div>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="dashboard-card bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 border-l-4 border-indigo-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-indigo-600">{{ \App\Models\Page::count() }}</span>
                <h3 class="text-base font-normal text-gray-500">الصفحات</h3>
            </div>
            <div class="ml-5 w-0 flex items-center justify-end flex-1">
                <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="dashboard-card bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-green-600">{{ \App\Models\Note::count() }}</span>
                <h3 class="text-base font-normal text-gray-500">الملاحظات</h3>
            </div>
            <div class="ml-5 w-0 flex items-center justify-end flex-1">
                <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="dashboard-card bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-yellow-600">{{ \App\Models\MealPlan::count() }}</span>
                <h3 class="text-base font-normal text-gray-500">الجداول الغذائية</h3>
            </div>
            <div class="ml-5 w-0 flex items-center justify-end flex-1">
                <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="dashboard-card bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-purple-600">{{ \App\Models\User::count() }}</span>
                <h3 class="text-base font-normal text-gray-500">المستخدمين</h3>
            </div>
            <div class="ml-5 w-0 flex items-center justify-end flex-1">
                <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Quick Access -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Activity -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">النشاط الأخير</h3>
                <span class="text-base font-normal text-gray-500">آخر النشاطات في النظام</span>
            </div>
            <div class="flex-shrink-0">
                <a href="#" class="text-sm font-medium text-indigo-600 hover:bg-gray-100 rounded-lg p-2">عرض الكل</a>
            </div>
        </div>
        <div class="flex flex-col mt-8">
            <div class="overflow-x-auto rounded-lg">
                <div class="align-middle inline-block min-w-full">
                    <div class="shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        النشاط
                                    </th>
                                    <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المستخدم
                                    </th>
                                    <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        التاريخ
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-900">
                                        تم إنشاء <span class="font-semibold">صفحة جديدة</span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                        أحمد محمد
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                        منذ 10 دقائق
                                    </td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-900">
                                        تم تعديل <span class="font-semibold">جدول غذائي</span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                        سارة أحمد
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                        منذ ساعة
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-900">
                                        تم تسجيل <span class="font-semibold">مستخدم جديد</span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                        محمد علي
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                        منذ 3 ساعات
                                    </td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-900">
                                        تم حذف <span class="font-semibold">ملاحظة</span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                        خالد عمر
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                        منذ 5 ساعات
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Access -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-2">الوصول السريع</h3>
        <span class="text-base font-normal text-gray-500">اختصارات للوظائف الأكثر استخداماً</span>
        
        <div class="grid grid-cols-2 gap-4 mt-8">
            <a href="{{ route('pages.create') }}" class="dashboard-card p-4 bg-indigo-50 rounded-lg shadow-sm flex flex-col items-center justify-center hover:bg-indigo-100">
                <svg class="w-8 h-8 text-indigo-600 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium text-gray-900">إنشاء صفحة</span>
            </a>
            
            <a href="{{ route('notes.create') }}" class="dashboard-card p-4 bg-green-50 rounded-lg shadow-sm flex flex-col items-center justify-center hover:bg-green-100">
                <svg class="w-8 h-8 text-green-600 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium text-gray-900">إضافة ملاحظة</span>
            </a>
            
            <a href="{{ route('meal-plans.create') }}" class="dashboard-card p-4 bg-yellow-50 rounded-lg shadow-sm flex flex-col items-center justify-center hover:bg-yellow-100">
                <svg class="w-8 h-8 text-yellow-600 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-900">إضافة وجبة</span>
            </a>
            
            @role('admin')
            <a href="{{ route('admin.permissions.index') }}" class="dashboard-card p-4 bg-purple-50 rounded-lg shadow-sm flex flex-col items-center justify-center hover:bg-purple-100">
                <svg class="w-8 h-8 text-purple-600 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium text-gray-900">إدارة الصلاحيات</span>
            </a>
            @endrole
        </div>
        
        <!-- System Status -->
        <div class="mt-8">
            <h4 class="text-base font-medium text-gray-900 mb-4">حالة النظام</h4>
            <div class="flex flex-col space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-sm font-medium text-gray-900">قاعدة البيانات</span>
                    </div>
                    <span class="text-sm text-gray-500">متصل</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-sm font-medium text-gray-900">خدمة التخزين</span>
                    </div>
                    <span class="text-sm text-gray-500">متصل</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-2.5 h-2.5 rounded-full bg-yellow-500 mr-2"></div>
                        <span class="text-sm font-medium text-gray-900">خدمة الإشعارات</span>
                    </div>
                    <span class="text-sm text-gray-500">بطيء</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Content -->
<div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">المحتوى الأخير</h3>
            <span class="text-base font-normal text-gray-500">آخر المحتويات التي تم إنشاؤها أو تعديلها</span>
        </div>
        <div class="flex-shrink-0">
            <a href="#" class="text-sm font-medium text-indigo-600 hover:bg-gray-100 rounded-lg p-2">عرض الكل</a>
        </div>
    </div>
    
    <div class="flex flex-col mt-8">
        <div class="overflow-x-auto rounded-lg">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    العنوان
                                </th>
                                <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    النوع
                                </th>
                                <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    المؤلف
                                </th>
                                <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    التاريخ
                                </th>
                                <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الحالة
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @php
                                try {
                                    $pages = \App\Models\Page::latest()->take(3)->get();
                                } catch (\Exception $e) {
                                    $pages = collect([]);
                                }
                                
                                try {
                                    $notes = \App\Models\Note::latest()->take(2)->get();
                                } catch (\Exception $e) {
                                    $notes = collect([]);
                                }
                                
                                try {
                                    $mealPlans = \App\Models\MealPlan::latest()->take(2)->get();
                                } catch (\Exception $e) {
                                    $mealPlans = collect([]);
                                }
                                
                                $combinedContent = collect();
                                
                                // Add pages with error handling
                                foreach ($pages as $item) {
                                    $combinedContent->push([
                                        'title' => $item->title,
                                        'type' => 'صفحة',
                                        'author' => $item->user->name ?? 'غير معروف',
                                        'date' => $item->created_at,
                                        'status' => $item->is_published ? 'منشور' : 'مسودة',
                                        'status_color' => $item->is_published ? 'green' : 'yellow',
                                        'url' => route('pages.edit', $item)
                                    ]);
                                }
                                
                                // Add notes with error handling
                                foreach ($notes as $item) {
                                    $combinedContent->push([
                                        'title' => $item->title,
                                        'type' => 'ملاحظة',
                                        'author' => $item->user->name ?? 'غير معروف',
                                        'date' => $item->created_at,
                                        'status' => 'نشط',
                                        'status_color' => 'blue',
                                        'url' => route('notes.edit', $item)
                                    ]);
                                }
                                
                                // Add meal plans with error handling
                                foreach ($mealPlans as $item) {
                                    $combinedContent->push([
                                        'title' => $item->name,
                                        'type' => 'وجبة',
                                        'author' => $item->user->name ?? 'غير معروف',
                                        'date' => $item->created_at,
                                        'status' => $item->is_active ? 'نشط' : 'غير نشط',
                                        'status_color' => $item->is_active ? 'green' : 'red',
                                        'url' => route('meal-plans.edit', $item)
                                    ]);
                                }
                                
                                // Sort by date if available
                                $combinedContent = $combinedContent->sortByDesc(function ($item) {
                                    return $item['date'] ?? now();
                                })->take(5);
                            @endphp
                            
                            @forelse($combinedContent as $item)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                    <td class="p-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ $item['url'] }}" class="text-indigo-600 hover:text-indigo-900">{{ $item['title'] }}</a>
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item['type'] }}
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item['author'] }}
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ isset($item['date']) && $item['date'] ? $item['date']->diffForHumans() : 'غير معروف' }}
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $item['status_color'] }}-100 text-{{ $item['status_color'] }}-800">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500">
                                        لا يوجد محتوى حديث
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection