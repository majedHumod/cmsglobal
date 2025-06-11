<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📁 {{ __('إدارة مجموعات الصلاحيات') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    ← العودة للصلاحيات
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- إنشاء مجموعة جديدة -->
            <div class="bg-white shadow sm:rounded-lg">
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
                                <input type="text" name="icon" id="icon" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="مثال: users, cog, shield">
                            </div>
                            
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700">لون المجموعة</label>
                                <input type="color" name="color" id="color" class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="#6366f1">
                            </div>
                        </div>
                        
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
                                <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                    تعديل
                                </button>
                                <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                                    حذف
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <p class="text-gray-500">لا توجد مجموعات صلاحيات متاحة</p>
                            <p class="text-sm text-gray-400 mt-2">قم بإنشاء مجموعة جديدة باستخدام النموذج أعلاه</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>