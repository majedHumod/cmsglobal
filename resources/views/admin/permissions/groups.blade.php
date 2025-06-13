@extends('layouts.admin')

@section('title', 'إدارة مجموعات الصلاحيات')

@section('breadcrumbs')
<li class="flex items-center">
    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
    <a href="{{ route('admin.permissions.index') }}" class="text-gray-700 hover:text-indigo-600 text-sm font-medium">
        الصلاحيات
    </a>
</li>
<li class="flex items-center">
    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
    <span class="text-gray-400 text-sm font-medium">
        إدارة المجموعات
    </span>
</li>
@endsection

@section('header', 'إدارة مجموعات الصلاحيات')

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        العودة للصلاحيات
    </a>
</div>
@endsection

@section('content')
<!-- إنشاء مجموعة جديدة -->
<div class="bg-white shadow sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">إنشاء مجموعة صلاحيات جديدة</h3>
        
        <form action="{{ route('admin.permissions.store-group') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">اسم المجموعة</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="sort_order" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="0">
                </div>
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف المجموعة</label>
                <textarea name="description" id="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700">أيقونة المجموعة</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <input type="text" name="icon" id="icon" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md border-gray-300" placeholder="مثال: users, cog, shield">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">اسم الأيقونة من مكتبة الأيقونات</p>
                </div>
                
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">لون المجموعة</label>
                    <div class="mt-1 flex items-center">
                        <input type="color" name="color" id="color" class="h-10 w-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="#6366f1">
                        <span class="ml-2 text-sm text-gray-500">اختر لون المجموعة</span>
                    </div>
                </div>
            </div>
            
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    إنشاء المجموعة
                </button>
            </div>
        </form>
    </div>
</div>

<!-- قائمة المجموعات -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">مجموعات الصلاحيات</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">قائمة بجميع مجموعات الصلاحيات المتاحة</p>
    </div>
    <div class="border-t border-gray-200">
        @forelse($groups as $group)
            <div class="px-4 py-5 sm:p-6 border-b border-gray-200 last:border-b-0">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full mr-3" style="background-color: {{ $group->color ?? '#6366f1' }}"></div>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">
                                @if(isset($group->icon) && $group->icon)
                                    <i class="{{ $group->icon }} mr-2"></i>
                                @endif
                                {{ $group->name }}
                            </h4>
                            @if(isset($group->description) && $group->description)
                                <p class="text-sm text-gray-500">{{ $group->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ isset($group->categories) ? count($group->categories) : 0 }} تصنيف
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ isset($group->is_active) && $group->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ isset($group->is_active) && $group->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                </div>
                
                <!-- تصنيفات المجموعة -->
                @if(isset($group->categories) && count($group->categories) > 0)
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">التصنيفات:</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($group->categories as $category)
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <h6 class="font-medium text-gray-800">{{ $category->name }}</h6>
                                    @if(isset($category->description) && $category->description)
                                        <p class="text-xs text-gray-500 mb-2">{{ $category->description }}</p>
                                    @endif
                                    
                                    @if(isset($category->permissions) && count($category->permissions) > 0)
                                        <div class="mt-2 space-y-1">
                                            @foreach($category->permissions as $permission)
                                                <div class="text-xs text-gray-600">• {{ $permission->name }}</div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-400 mt-2">لا توجد صلاحيات</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">لا توجد تصنيفات في هذه المجموعة.</p>
                @endif
                
                <!-- أزرار التحكم -->
                <div class="mt-4 flex justify-end space-x-2">
                    <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                        تعديل
                    </button>
                    <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        حذف
                    </button>
                </div>
            </div>
        @empty
            <div class="px-4 py-8 text-center">
                <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                    <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">لا توجد مجموعات صلاحيات متاحة</p>
                <p class="text-sm text-gray-400 mt-2">قم بإنشاء مجموعة جديدة باستخدام النموذج أعلاه</p>
            </div>
        @endforelse
    </div>
</div>
@endsection