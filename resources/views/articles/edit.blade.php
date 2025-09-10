@extends('layouts.admin')

@section('title', 'تعديل المقال')

@section('header', 'تعديل المقال: ' . $article->title)

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('articles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
            <h2 class="text-lg font-medium text-gray-900">تعديل المقال</h2>
            <p class="mt-1 text-sm text-gray-500">قم بتعديل محتوى ومعلومات المقال.</p>
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

        <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- معلومات المقال الأساسية -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900">معلومات المقال الأساسية</h3>
                <p class="mt-1 text-sm text-gray-500">تعديل المعلومات الأساسية للمقال.</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- عنوان المقال -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">عنوان المقال *</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('title', $article->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- صورة المقال -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">صورة المقال</label>
                        @if($article->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="w-32 h-32 object-cover rounded">
                                <p class="text-sm text-gray-500 mt-1">الصورة الحالية</p>
                            </div>
                        @endif
                        <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- محتوى المقال -->
            <div class="border-b border-gray-200 py-6">
                <h3 class="text-lg font-medium text-gray-900">محتوى المقال</h3>
                <p class="mt-1 text-sm text-gray-500">تعديل محتوى المقال.</p>
                
                <div class="mt-6">
                    <label for="content" class="block text-sm font-medium text-gray-700">محتوى المقال *</label>
                    <textarea name="content" id="content" rows="12" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- معلومات المقال -->
            <div class="py-6">
                <h3 class="text-lg font-medium text-gray-900">معلومات المقال</h3>
                <p class="mt-1 text-sm text-gray-500">معلومات إضافية عن المقال.</p>
                
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $article->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">آخر تحديث</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $article->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">المؤلف</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $article->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">عدد الكلمات</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ str_word_count(strip_tags($article->content)) }} كلمة</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('articles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    تحديث المقال
                </button>
            </div>
        </form>
    </div>
</div>
@endsection