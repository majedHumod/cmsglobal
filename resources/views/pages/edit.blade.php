<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل الصفحة: ') . $page->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('pages.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- العنوان -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الصفحة *</label>
                            <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('title', $page->title) }}" required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

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
                                    &lt;/&gt; كود
                                </button>
                            </div>

                            <!-- منطقة المحرر -->
                            <div id="editor-container" class="border-l border-r border-b border-gray-300 rounded-b-md">
                                <div id="editor" contenteditable="true" class="min-h-96 p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="direction: rtl;">
                                    {!! old('content', $page->content) !!}
                                </div>
                                <textarea name="content" id="content-textarea" class="hidden w-full min-h-96 p-4 border-0 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="direction: rtl;">{!! old('content', $page->content) !!}</textarea>
                            </div>
                            
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">استخدم أدوات التنسيق أعلاه لتنسيق المحتوى</p>
                        </div>

                        <!-- المقتطف -->
                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">مقتطف قصير</label>
                            <textarea name="excerpt" id="excerpt" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="وصف مختصر للصفحة">{{ old('excerpt', $page->excerpt) }}</textarea>
                            @error('excerpt')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- عنوان SEO -->
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">عنوان SEO</label>
                                <input type="text" name="meta_title" id="meta_title" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('meta_title', $page->meta_title) }}" placeholder="عنوان محرك البحث">
                                @error('meta_title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- الصورة المميزة -->
                            <div>
                                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">الصورة المميزة</label>
                                @if($page->featured_image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-32 h-32 object-cover rounded">
                                        <p class="text-sm text-gray-500 mt-1">الصورة الحالية</p>
                                    </div>
                                @endif
                                <input type="file" name="featured_image" id="featured_image" accept="image/*" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('featured_image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- وصف SEO -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">وصف SEO</label>
                            <textarea name="meta_description" id="meta_description" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="وصف الصفحة لمحركات البحث (160 حرف كحد أقصى)">{{ old('meta_description', $page->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- إعدادات الوصول -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">إعدادات الوصول والصلاحيات</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- مستوى الوصول -->
                                <div>
                                    <label for="access_level" class="block text-sm font-medium text-gray-700 mb-2">مستوى الوصول *</label>
                                    <select name="access_level" id="access_level" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="public" {{ old('access_level', $page->access_level) == 'public' ? 'selected' : '' }}>🌍 عام للجميع</option>
                                        <option value="authenticated" {{ old('access_level', $page->access_level) == 'authenticated' ? 'selected' : '' }}>🔐 المستخدمين المسجلين</option>
                                        <option value="user" {{ old('access_level', $page->access_level) == 'user' ? 'selected' : '' }}>👤 المستخدمين العاديين</option>
                                        <option value="page_manager" {{ old('access_level', $page->access_level) == 'page_manager' ? 'selected' : '' }}>📝 مديري الصفحات</option>
                                        <option value="admin" {{ old('access_level', $page->access_level) == 'admin' ? 'selected' : '' }}>👑 المديرين فقط</option>
                                       <option value="membership" {{ old('access_level', $page->access_level) == 'membership' ? 'selected' : '' }}>💎 أعضاء العضويات المدفوعة</option>
                                    </select>
                                    @error('access_level')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1">حدد من يستطيع الوصول لهذه الصفحة</p>
                                </div>

                                <!-- محتوى مدفوع -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع المحتوى</label>
                                    <div class="flex items-center">
                                        <input type="hidden" name="is_premium" value="0">
                                        <input type="checkbox" name="is_premium" id="is_premium" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('is_premium', $page->is_premium) ? 'checked' : '' }}>
                                        <label for="is_premium" class="ml-2 block text-sm text-gray-700">💎 محتوى مدفوع</label>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">سيتم تطبيق هذا لاحقاً مع نظام العضويات</p>
                                </div>
                            </div>
                           
                           <!-- أنواع العضويات المطلوبة -->
                           <div class="mt-6" id="membership-types-section" style="{{ old('access_level', $page->access_level) == 'membership' ? 'display:block' : 'display:none' }}">
                               <label class="block text-sm font-medium text-gray-700 mb-2">أنواع العضويات المطلوبة</label>
                               <p class="text-xs text-gray-500 mb-3">حدد أنواع العضويات التي يمكنها الوصول لهذه الصفحة</p>
                               
                               <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                   @php
                                       try {
                                           $membershipTypes = \App\Models\MembershipType::where('is_active', true)->orderBy('sort_order')->get();
                                           $pageRequiredMembershipTypes = old('required_membership_types', $page->required_membership_types ?? []);
                                           if (is_string($pageRequiredMembershipTypes)) {
                                               $pageRequiredMembershipTypes = json_decode($pageRequiredMembershipTypes, true) ?? [];
                                           }
                                       } catch (\Exception $e) {
                                           $membershipTypes = collect([]);
                                           $pageRequiredMembershipTypes = [];
                                       }
                                   @endphp
                                   
                                   @forelse($membershipTypes as $membershipType)
                                       <div class="flex items-center">
                                           <input type="checkbox" name="required_membership_types[]" id="membership_{{ $membershipType->id }}" value="{{ $membershipType->id }}" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               {{ in_array($membershipType->id, $pageRequiredMembershipTypes) ? 'checked' : '' }}>
                                           <label for="membership_{{ $membershipType->id }}" class="ml-2 block text-sm text-gray-700">
                                               {{ $membershipType->name }}
                                               @if($membershipType->price > 0)
                                                   <span class="text-xs text-gray-500">({{ $membershipType->formatted_price }})</span>
                                               @else
                                                   <span class="text-xs text-green-500">(مجاني)</span>
                                               @endif
                                           </label>
                                       </div>
                                   @empty
                                       <div class="col-span-3">
                                           <p class="text-sm text-gray-500">لا توجد أنواع عضويات متاحة</p>
                                       </div>
                                   @endforelse
                               </div>
                           </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- ترتيب القائمة -->
                            <div>
                                <label for="menu_order" class="block text-sm font-medium text-gray-700 mb-2">ترتيب القائمة</label>
                                <input type="number" name="menu_order" id="menu_order" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('menu_order', $page->menu_order) }}">
                                @error('menu_order')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- تاريخ النشر -->
                            <div>
                                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النشر</label>
                                <input type="datetime-local" name="published_at" id="published_at" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('published_at', $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}">
                                @error('published_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- خيارات النشر -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_published" id="is_published" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                                <label for="is_published" class="ml-2 block text-sm text-gray-700">نشر الصفحة</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="show_in_menu" id="show_in_menu" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('show_in_menu', $page->show_in_menu) ? 'checked' : '' }}>
                                <label for="show_in_menu" class="ml-2 block text-sm text-gray-700">إظهار في قائمة التنقل</label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('pages.index') }}" class="text-gray-500 hover:text-gray-700">إلغاء</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                تحديث الصفحة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

    <script>
        // إظهار/إخفاء قسم العضويات المطلوبة بناءً على مستوى الوصول
        document.addEventListener('DOMContentLoaded', function() {
            const accessLevelSelect = document.getElementById('access_level');
            const membershipTypesSection = document.getElementById('membership-types-section');
            const membershipCheckboxes = document.querySelectorAll('input[name="required_membership_types[]"]');
            
            function updateMembershipSection() {
                if (accessLevelSelect.value === 'membership') {
                    membershipTypesSection.style.display = 'block';
                    // Make at least one checkbox required when membership is selected
                    membershipCheckboxes.forEach(checkbox => {
                        checkbox.setAttribute('required', 'required');
                    });
                } else {
                    membershipTypesSection.style.display = 'none';
                    // Remove required attribute when membership is not selected
                    membershipCheckboxes.forEach(checkbox => {
                        checkbox.removeAttribute('required');
                    });
                }
            }
            
            if (accessLevelSelect && membershipTypesSection) {
                accessLevelSelect.addEventListener('change', function() {
                    updateMembershipSection();
                });
                
                // Run once on page load
                updateMembershipSection();
            }
            
            // Make checkboxes behave as a group for the required attribute
            if (membershipCheckboxes.length > 0) {
                membershipCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const anyChecked = Array.from(membershipCheckboxes).some(cb => cb.checked);
                        if (anyChecked) {
                            membershipCheckboxes.forEach(cb => cb.removeAttribute('required'));
                        } else if (accessLevelSelect.value === 'membership') {
                            membershipCheckboxes.forEach(cb => cb.setAttribute('required', 'required'));
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>