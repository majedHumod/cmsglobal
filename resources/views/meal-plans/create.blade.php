<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إضافة وجبة جديدة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('meal-plans.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- اسم الوجبة -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الوجبة *</label>
                                <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- نوع الوجبة -->
                            <div>
                                <label for="meal_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الوجبة *</label>
                                <select name="meal_type" id="meal_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">اختر نوع الوجبة</option>
                                    <option value="breakfast" {{ old('meal_type') == 'breakfast' ? 'selected' : '' }}>إفطار</option>
                                    <option value="lunch" {{ old('meal_type') == 'lunch' ? 'selected' : '' }}>غداء</option>
                                    <option value="dinner" {{ old('meal_type') == 'dinner' ? 'selected' : '' }}>عشاء</option>
                                    <option value="snack" {{ old('meal_type') == 'snack' ? 'selected' : '' }}>وجبة خفيفة</option>
                                </select>
                                @error('meal_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- وصف الوجبة -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الوجبة</label>
                            <textarea name="description" id="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- السعرات الحرارية -->
                            <div>
                                <label for="calories" class="block text-sm font-medium text-gray-700 mb-2">السعرات الحرارية</label>
                                <input type="number" name="calories" id="calories" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('calories') }}">
                                @error('calories')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- وقت التحضير -->
                            <div>
                                <label for="prep_time" class="block text-sm font-medium text-gray-700 mb-2">وقت التحضير (دقيقة)</label>
                                <input type="number" name="prep_time" id="prep_time" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('prep_time') }}">
                                @error('prep_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- وقت الطبخ -->
                            <div>
                                <label for="cook_time" class="block text-sm font-medium text-gray-700 mb-2">وقت الطبخ (دقيقة)</label>
                                <input type="number" name="cook_time" id="cook_time" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('cook_time') }}">
                                @error('cook_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- عدد الحصص -->
                            <div>
                                <label for="servings" class="block text-sm font-medium text-gray-700 mb-2">عدد الحصص *</label>
                                <input type="number" name="servings" id="servings" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('servings', 1) }}" required>
                                @error('servings')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- مستوى الصعوبة -->
                            <div>
                                <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">مستوى الصعوبة *</label>
                                <select name="difficulty" id="difficulty" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>سهل</option>
                                    <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>متوسط</option>
                                    <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>صعب</option>
                                </select>
                                @error('difficulty')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- صورة الوجبة -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">صورة الوجبة</label>
                                <input type="file" name="image" id="image" accept="image/*" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- المكونات -->
                        <div>
                            <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-2">المكونات *</label>
                            <textarea name="ingredients" id="ingredients" rows="5" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="اكتب كل مكون في سطر منفصل" required>{{ old('ingredients') }}</textarea>
                            @error('ingredients')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- طريقة التحضير -->
                        <div>
                            <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">طريقة التحضير</label>
                            <textarea name="instructions" id="instructions" rows="6" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="اكتب خطوات التحضير بالتفصيل">{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- حالة النشر -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">نشر الوجبة (جعلها مرئية للجمهور)</label>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('meal-plans.index') }}" class="text-gray-500 hover:text-gray-700">إلغاء</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                حفظ الوجبة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>