@extends('layouts.admin')

@section('title', 'إضافة ملاحظة جديدة')

@section('header', 'إضافة ملاحظة جديدة')

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('notes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
            <h2 class="text-lg font-medium text-gray-900">إنشاء ملاحظة جديدة</h2>
            <p class="mt-1 text-sm text-gray-500">قم بإضافة ملاحظة جديدة لتنظيم أفكارك ومعلوماتك.</p>
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

        <form action="{{ route('notes.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- معلومات الملاحظة الأساسية -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900">معلومات الملاحظة</h3>
                <p class="mt-1 text-sm text-gray-500">أدخل عنوان ومحتوى الملاحظة.</p>
                
                <div class="mt-6">
                    <!-- عنوان الملاحظة -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">عنوان الملاحظة *</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('title') }}" required placeholder="أدخل عنوان الملاحظة">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">اختر عنواناً واضحاً ومفهوماً للملاحظة.</p>
                    </div>
                </div>
            </div>
            
            <!-- محتوى الملاحظة -->
            <div class="border-b border-gray-200 py-6">
                <h3 class="text-lg font-medium text-gray-900">محتوى الملاحظة</h3>
                <p class="mt-1 text-sm text-gray-500">أدخل تفاصيل ومحتوى الملاحظة.</p>
                
                <div class="mt-6">
                    <!-- المحتوى مع المحرر المجاني -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">محتوى الملاحظة *</label>
                        
                        <!-- أدوات التنسيق -->
                        <div class="border border-gray-300 rounded-t-md bg-gray-50 p-2 flex flex-wrap gap-1" id="editor-toolbar">
                            <button type="button" onclick="formatText('bold')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="غامق">
                                <strong>B</strong>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="مائل">
                                <em>I</em>
                            </button>
                            <button type="button" onclick="formatText('underline')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="تسطير">
                                <u>U</u>
                            </button>
                            <div class="border-l border-gray-300 mx-1"></div>
                            <button type="button" onclick="formatText('insertUnorderedList')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="قائمة نقطية">
                                • قائمة
                            </button>
                            <button type="button" onclick="formatText('insertOrderedList')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="قائمة مرقمة">
                                1. قائمة
                            </button>
                            <div class="border-l border-gray-300 mx-1"></div>
                            <button type="button" onclick="formatText('justifyRight')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="محاذاة يمين">
                                →
                            </button>
                            <button type="button" onclick="formatText('justifyCenter')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="محاذاة وسط">
                                ↔
                            </button>
                            <button type="button" onclick="formatText('justifyLeft')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="محاذاة يسار">
                                ←
                            </button>
                            <div class="border-l border-gray-300 mx-1"></div>
                            <button type="button" onclick="insertLink()" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="إدراج رابط">
                                🔗 رابط
                            </button>
                            <button type="button" onclick="toggleSourceCode()" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="عرض الكود">
                                </> كود
                            </button>
                        </div>

                        <!-- منطقة المحرر -->
                        <div id="editor-container" class="border-l border-r border-b border-gray-300 rounded-b-md">
                            <div id="editor" contenteditable="true" class="min-h-48 p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="direction: rtl;">
                                {{ old('content') }}
                            </div>
                            <textarea name="content" id="content-textarea" class="hidden w-full min-h-48 p-4 border-0 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="direction: rtl;">{{ old('content') }}</textarea>
                        </div>
                        
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">استخدم أدوات التنسيق أعلاه لتنسيق محتوى الملاحظة.</p>
                    </div>
                </div>
            </div>
            
            <!-- إعدادات الحفظ -->
            <div class="py-6">
                <h3 class="text-lg font-medium text-gray-900">إعدادات الحفظ</h3>
                <p class="mt-1 text-sm text-gray-500">تأكد من صحة البيانات قبل الحفظ.</p>
                
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">نصائح للكتابة</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>استخدم عناوين واضحة ومفهومة</li>
                                    <li>نظم المحتوى باستخدام القوائم والفقرات</li>
                                    <li>أضف الروابط المهمة عند الحاجة</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('notes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    حفظ الملاحظة
                </button>
            </div>
        </form>
    </div>
</div>

<!-- محرر مجاني بالكامل -->
<script>
    let isSourceMode = false;
    const editor = document.getElementById('editor');
    const textarea = document.getElementById('content-textarea');

    // تحديث المحتوى في الـ textarea عند التغيير
    editor.addEventListener('input', function() {
        if (!isSourceMode) {
            textarea.value = editor.innerHTML;
        }
    });

    // دالة تنسيق النص
    function formatText(command, value = null) {
        if (isSourceMode) return;
        
        document.execCommand(command, false, value);
        editor.focus();
        textarea.value = editor.innerHTML;
    }

    // دالة إدراج رابط
    function insertLink() {
        if (isSourceMode) return;
        
        const url = prompt('أدخل رابط URL:');
        if (url) {
            formatText('createLink', url);
        }
    }

    // تبديل وضع عرض الكود
    function toggleSourceCode() {
        isSourceMode = !isSourceMode;
        
        if (isSourceMode) {
            // التبديل إلى وضع الكود
            textarea.value = editor.innerHTML;
            editor.style.display = 'none';
            textarea.style.display = 'block';
            textarea.classList.remove('hidden');
        } else {
            // التبديل إلى وضع المحرر
            editor.innerHTML = textarea.value;
            editor.style.display = 'block';
            textarea.style.display = 'none';
            textarea.classList.add('hidden');
        }
    }

    // تحديث المحتوى قبل إرسال النموذج
    document.querySelector('form').addEventListener('submit', function() {
        if (isSourceMode) {
            editor.innerHTML = textarea.value;
        } else {
            textarea.value = editor.innerHTML;
        }
    });

    // تحسين تجربة المستخدم
    editor.addEventListener('paste', function(e) {
        e.preventDefault();
        const text = e.clipboardData.getData('text/plain');
        document.execCommand('insertText', false, text);
    });

    // إضافة أنماط CSS للمحرر
    const style = document.createElement('style');
    style.textContent = `
        #editor {
            line-height: 1.6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        #editor h1 { font-size: 2em; font-weight: bold; margin: 0.5em 0; }
        #editor h2 { font-size: 1.5em; font-weight: bold; margin: 0.5em 0; }
        #editor h3 { font-size: 1.2em; font-weight: bold; margin: 0.5em 0; }
        #editor p { margin: 0.5em 0; }
        #editor ul, #editor ol { margin: 0.5em 0; padding-right: 2em; }
        #editor li { margin: 0.2em 0; }
        #editor a { color: #3b82f6; text-decoration: underline; }
        #editor blockquote { 
            border-right: 4px solid #e5e7eb; 
            padding-right: 1em; 
            margin: 1em 0; 
            font-style: italic; 
            background: #f9fafb; 
            padding: 1em; 
        }
    `;
    document.head.appendChild(style);
</script>
@endsection