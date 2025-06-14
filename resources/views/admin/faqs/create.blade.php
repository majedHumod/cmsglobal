@extends('layouts.admin')

@section('title', 'إضافة سؤال شائع جديد')

@section('header', 'إضافة سؤال شائع جديد')

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.faqs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
        <form action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- السؤال -->
                <div class="md:col-span-2">
                    <label for="question" class="block text-sm font-medium text-gray-700 mb-2">السؤال *</label>
                    <input type="text" name="question" id="question" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('question') }}" required>
                    @error('question')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- التصنيف -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">التصنيف *</label>
                    <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="عام" {{ old('category') == 'عام' ? 'selected' : '' }}>عام</option>
                        <option value="العضويات" {{ old('category') == 'العضويات' ? 'selected' : '' }}>العضويات</option>
                        <option value="الدفع" {{ old('category') == 'الدفع' ? 'selected' : '' }}>الدفع</option>
                        <option value="الحساب" {{ old('category') == 'الحساب' ? 'selected' : '' }}>الحساب</option>
                        <option value="المحتوى" {{ old('category') == 'المحتوى' ? 'selected' : '' }}>المحتوى</option>
                        <option value="الدعم الفني" {{ old('category') == 'الدعم الفني' ? 'selected' : '' }}>الدعم الفني</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- ترتيب العرض -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="sort_order" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('sort_order', 0) }}">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- الإجابة -->
            <div>
                <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">الإجابة *</label>
                
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
                </div>

                <!-- منطقة المحرر -->
                <div id="editor-container" class="border-l border-r border-b border-gray-300 rounded-b-md">
                    <div id="editor" contenteditable="true" class="min-h-32 p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="direction: rtl;">
                        {{ old('answer') }}
                    </div>
                    <textarea name="answer" id="answer-textarea" class="hidden w-full min-h-32 p-4 border-0 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="direction: rtl;">{{ old('answer') }}</textarea>
                </div>
                
                @error('answer')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- حالة النشر -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-gray-700">تفعيل السؤال (جعله مرئي للجمهور)</label>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.faqs.index') }}" class="text-gray-500 hover:text-gray-700">إلغاء</a>
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    حفظ السؤال
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let isSourceMode = false;
    const editor = document.getElementById('editor');
    const textarea = document.getElementById('answer-textarea');

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

    // تحديث المحتوى قبل إرسال النموذج
    document.querySelector('form').addEventListener('submit', function() {
        textarea.value = editor.innerHTML;
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
        #editor p { margin: 0.5em 0; }
        #editor ul, #editor ol { margin: 0.5em 0; padding-right: 2em; }
        #editor li { margin: 0.2em 0; }
        #editor a { color: #3b82f6; text-decoration: underline; }
    `;
    document.head.appendChild(style);
</script>
@endsection