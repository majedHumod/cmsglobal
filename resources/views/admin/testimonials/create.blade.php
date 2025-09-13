@extends('layouts.admin')

@section('title', 'إضافة قصة نجاح جديدة')

@section('header', 'إضافة قصة نجاح جديدة')

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        العودة للقائمة
    </a>
</div>
@endsection

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900">إنشاء قصة نجاح جديدة</h2>
            <p class="mt-1 text-sm text-gray-500">قم بإضافة قصة نجاح جديدة لعرضها في الصفحة الرئيسية.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <strong class="font-bold">خطأ في البيانات!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- معلومات قصة النجاح الأساسية -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900">معلومات قصة النجاح</h3>
                <p class="mt-1 text-sm text-gray-500">أدخل معلومات قصة النجاح الأساسية.</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اسم الشخص -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">اسم الشخص *</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('name') }}" required placeholder="أدخل اسم الشخص">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ترتيب العرض -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">ترتيب العرض</label>
                        <input type="number" name="sort_order" id="sort_order" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('sort_order', 0) }}">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">الرقم الأصغر يظهر أولاً.</p>
                    </div>
                </div>

                <!-- صورة القصة -->
                <div class="mt-6">
                    <label for="image" class="block text-sm font-medium text-gray-700">صورة القصة</label>
                    <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">صورة عامة تعبر عن قصة النجاح (اختياري).</p>
                </div>
            </div>
            
            <!-- محتوى قصة النجاح -->
            <div class="border-b border-gray-200 py-6">
                <h3 class="text-lg font-medium text-gray-900">محتوى قصة النجاح</h3>
                <p class="mt-1 text-sm text-gray-500">اكتب تفاصيل قصة النجاح.</p>
                
                <div class="mt-6">
                    <label for="story_content" class="block text-sm font-medium text-gray-700">محتوى القصة *</label>
                    <textarea name="story_content" id="story_content" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="اكتب قصة النجاح بالتفصيل..." style="direction: rtl;">{{ old('story_content') }}</textarea>
                    @error('story_content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">اكتب قصة النجاح بطريقة ملهمة ومؤثرة.</p>
                </div>
            </div>
            
            <!-- إعدادات العرض -->
            <div class="py-6">
                <h3 class="text-lg font-medium text-gray-900">إعدادات العرض</h3>
                <p class="mt-1 text-sm text-gray-500">حدد إعدادات عرض قصة النجاح.</p>
                
                <div class="mt-6">
                    <!-- حالة الإظهار -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_visible" id="is_visible" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('is_visible', true) ? 'checked' : '' }}>
                        </div>
                        <div class="mr-3 text-sm">
                            <label for="is_visible" class="font-medium text-gray-700">إظهار قصة النجاح</label>
                            <p class="text-gray-500">عند التفعيل، ستظهر قصة النجاح في الصفحة الرئيسية.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="mr-3">
                            <h3 class="text-sm font-medium text-blue-800">نصائح لكتابة قصة النجاح</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>اكتب القصة بطريقة شخصية ومؤثرة</li>
                                    <li>ركز على النتائج والتحسينات المحققة</li>
                                    <li>استخدم لغة بسيطة وواضحة</li>
                                    <li>أضف تفاصيل محددة وقابلة للتصديق</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    حفظ قصة النجاح
                </button>
            </div>
        </form>
    </div>
</div>
@endsection