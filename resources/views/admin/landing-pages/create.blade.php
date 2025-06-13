@extends('layouts.admin')

@section('title', 'إنشاء صفحة رئيسية جديدة')

@section('header', 'إنشاء صفحة رئيسية جديدة')

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.landing-pages.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
        <form action="{{ route('admin.landing-pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900">معلومات الهيدر</h3>
                <p class="mt-1 text-sm text-gray-500">قم بتخصيص هيدر الصفحة الرئيسية.</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- العنوان -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">العنوان الرئيسي *</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- العنوان الفرعي -->
                    <div>
                        <label for="subtitle" class="block text-sm font-medium text-gray-700">العنوان الفرعي</label>
                        <input type="text" name="subtitle" id="subtitle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('subtitle') }}">
                        @error('subtitle')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6">
                    <!-- صورة الهيدر -->
                    <div>
                        <label for="header_image" class="block text-sm font-medium text-gray-700">صورة الهيدر *</label>
                        <div class="mt-1 flex items-center">
                            <input type="file" name="header_image" id="header_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required accept="image/*">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">يفضل أن تكون الصورة بأبعاد 1920×600 بكسل.</p>
                        @error('header_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- لون نص الهيدر -->
                    <div>
                        <label for="header_text_color" class="block text-sm font-medium text-gray-700">لون نص الهيدر *</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="color" name="header_text_color" id="header_text_color" class="h-10 w-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('header_text_color', '#ffffff') }}">
                            <input type="text" name="header_text_color_text" id="header_text_color_text" class="ml-2 flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('header_text_color', '#ffffff') }}" readonly>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">لون النص الذي سيظهر فوق صورة الهيدر.</p>
                        @error('header_text_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="border-b border-gray-200 py-6">
                <h3 class="text-lg font-medium text-gray-900">إعدادات زر الانضمام</h3>
                <p class="mt-1 text-sm text-gray-500">قم بتخصيص زر "انضم لنا" في الهيدر.</p>
                
                <div class="mt-6">
                    <!-- إظهار زر الانضمام -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="show_join_button" id="show_join_button" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('show_join_button', true) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="show_join_button" class="font-medium text-gray-700">إظهار زر الانضمام</label>
                            <p class="text-gray-500">تفعيل زر "انضم لنا" في الهيدر.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- نص زر الانضمام -->
                    <div>
                        <label for="join_button_text" class="block text-sm font-medium text-gray-700">نص الزر</label>
                        <input type="text" name="join_button_text" id="join_button_text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('join_button_text', 'انضم لنا') }}">
                        @error('join_button_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- رابط زر الانضمام -->
                    <div>
                        <label for="join_button_url" class="block text-sm font-medium text-gray-700">رابط الزر</label>
                        <input type="text" name="join_button_url" id="join_button_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('join_button_url', route('register')) }}">
                        @error('join_button_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- لون زر الانضمام -->
                    <div>
                        <label for="join_button_color" class="block text-sm font-medium text-gray-700">لون الزر</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="color" name="join_button_color" id="join_button_color" class="h-10 w-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('join_button_color', '#3b82f6') }}">
                            <input type="text" name="join_button_color_text" id="join_button_color_text" class="ml-2 flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('join_button_color', '#3b82f6') }}" readonly>
                        </div>
                        @error('join_button_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="border-b border-gray-200 py-6">
                <h3 class="text-lg font-medium text-gray-900">محتوى الصفحة</h3>
                <p class="mt-1 text-sm text-gray-500">أدخل محتوى الصفحة الرئيسية.</p>
                
                <div class="mt-6">
                    <!-- المحتوى مع المحرر المجاني -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">محتوى الصفحة *</label>
                        
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
                            <button type="button" onclick="formatText('justifyLeft')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="محاذاة يسار">
                                ←
                            </button>
                            <button type="button" onclick="formatText('justifyCenter')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="محاذاة وسط">
                                ↔
                            </button>
                            <button type="button" onclick="formatText('justifyRight')" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="محاذاة يمين">
                                →
                            </button>
                            <div class="border-l border-gray-300 mx-1"></div>
                            <button type="button" onclick="insertLink()" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="إدراج رابط">
                                🔗 رابط
                            </button>
                            <button type="button" onclick="insertImage()" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="إدراج صورة">
                                🖼️ صورة
                            </button>
                            <div class="border-l border-gray-300 mx-1"></div>
                            <select onchange="formatHeading(this.value)" class="px-2 py-1 bg-white border border-gray-300 rounded text-sm">
                                <option value="">العناوين</option>
                                <option value="h1">عنوان رئيسي</option>
                                <option value="h2">عنوان فرعي</option>
                                <option value="h3">عنوان صغير</option>
                                <option value="p">نص عادي</option>
                            </select>
                            <div class="border-l border-gray-300 mx-1"></div>
                            <button type="button" onclick="toggleSourceCode()" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100" title="عرض الكود">
                                </> كود
                            </button>
                        </div>

                        <!-- منطقة المحرر -->
                        <div id="editor-container" class="border-l border-r border-b border-gray-300 rounded-b-md">
                            <div id="editor" contenteditable="true" class="min-h-96 p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="direction: rtl;">
                                {{ old('content') }}
                            </div>
                            <textarea name="content" id="content-textarea" class="hidden w-full min-h-96 p-4 border-0 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="direction: rtl;">{{ old('content') }}</textarea>
                        </div>
                        
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="border-b border-gray-200 py-6">
                <h3 class="text-lg font-medium text-gray-900">إعدادات SEO</h3>
                <p class="mt-1 text-sm text-gray-500">قم بتخصيص إعدادات محركات البحث.</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- عنوان SEO -->
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700">عنوان SEO</label>
                        <input type="text" name="meta_title" id="meta_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('meta_title') }}">
                        <p class="mt-1 text-sm text-gray-500">إذا تُرك فارغاً، سيتم استخدام العنوان الرئيسي.</p>
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- وصف SEO -->
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700">وصف SEO</label>
                        <input type="text" name="meta_description" id="meta_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('meta_description') }}">
                        <p class="mt-1 text-sm text-gray-500">وصف قصير للصفحة يظهر في نتائج البحث.</p>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="py-6">
                <h3 class="text-lg font-medium text-gray-900">إعدادات النشر</h3>
                <p class="mt-1 text-sm text-gray-500">قم بتحديد حالة نشر الصفحة.</p>
                
                <div class="mt-6">
                    <!-- تفعيل الصفحة -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_active" id="is_active" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('is_active') ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-700">تفعيل الصفحة</label>
                            <p class="text-gray-500">عند تفعيل هذه الصفحة، سيتم إلغاء تفعيل أي صفحة رئيسية أخرى.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    حفظ الصفحة الرئيسية
                </button>
            </div>
        </form>
    </div>
</div>

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

    // دالة تنسيق العناوين
    function formatHeading(tag) {
        if (isSourceMode || !tag) return;
        
        formatText('formatBlock', tag);
    }

    // دالة إدراج رابط
    function insertLink() {
        if (isSourceMode) return;
        
        const url = prompt('أدخل رابط URL:');
        if (url) {
            formatText('createLink', url);
        }
    }

    // دالة إدراج صورة
    function insertImage() {
        if (isSourceMode) return;
        
        const url = prompt('أدخل رابط الصورة:');
        if (url) {
            formatText('insertImage', url);
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

    // Color picker sync
    const headerTextColor = document.getElementById('header_text_color');
    const headerTextColorText = document.getElementById('header_text_color_text');
    const joinButtonColor = document.getElementById('join_button_color');
    const joinButtonColorText = document.getElementById('join_button_color_text');
    
    headerTextColor.addEventListener('input', function() {
        headerTextColorText.value = this.value;
    });
    
    headerTextColorText.addEventListener('input', function() {
        headerTextColor.value = this.value;
    });
    
    joinButtonColor.addEventListener('input', function() {
        joinButtonColorText.value = this.value;
    });
    
    joinButtonColorText.addEventListener('input', function() {
        joinButtonColor.value = this.value;
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
        #editor img { max-width: 100%; height: auto; margin: 0.5em 0; }
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