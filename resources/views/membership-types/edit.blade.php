<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل نوع العضوية: ') . $membershipType->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if($membershipType->is_protected)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">تحذير!</strong>
                            <span class="block sm:inline">هذا النوع من العضوية محمي من النظام ولا يمكن تعديله.</span>
                        </div>
                    @endif

                    <form action="{{ route('membership-types.update', $membershipType) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- اسم العضوية -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم نوع العضوية *</label>
                                <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('name', $membershipType->name) }}" required {{ $membershipType->is_protected ? 'readonly' : '' }}>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ترتيب العرض -->
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">ترتيب العرض</label>
                                <input type="number" name="sort_order" id="sort_order" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('sort_order', $membershipType->sort_order) }}" {{ $membershipType->is_protected ? 'readonly' : '' }}>
                                @error('sort_order')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- وصف العضوية -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف العضوية</label>
                            <textarea name="description" id="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="وصف مختصر لنوع العضوية" {{ $membershipType->is_protected ? 'readonly' : '' }}>{{ old('description', $membershipType->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- السعر -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">سعر الاشتراك (ريال) *</label>
                                <input type="number" name="price" id="price" step="0.01" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('price', $membershipType->price) }}" required {{ $membershipType->is_protected ? 'readonly' : '' }}>
                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">ضع 0 للعضوية المجانية</p>
                            </div>

                            <!-- مدة الاشتراك -->
                            <div>
                                <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">مدة الاشتراك (بالأيام) *</label>
                                <select name="duration_days" id="duration_days" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required {{ $membershipType->is_protected ? 'disabled' : '' }}>
                                    <option value="7" {{ old('duration_days', $membershipType->duration_days) == 7 ? 'selected' : '' }}>أسبوع واحد (7 أيام)</option>
                                    <option value="30" {{ old('duration_days', $membershipType->duration_days) == 30 ? 'selected' : '' }}>شهر واحد (30 يوم)</option>
                                    <option value="90" {{ old('duration_days', $membershipType->duration_days) == 90 ? 'selected' : '' }}>3 أشهر (90 يوم)</option>
                                    <option value="180" {{ old('duration_days', $membershipType->duration_days) == 180 ? 'selected' : '' }}>6 أشهر (180 يوم)</option>
                                    <option value="365" {{ old('duration_days', $membershipType->duration_days) == 365 ? 'selected' : '' }}>سنة واحدة (365 يوم)</option>
                                    <option value="custom" {{ !in_array($membershipType->duration_days, [7, 30, 90, 180, 365]) ? 'selected' : '' }}>مدة مخصصة</option>
                                </select>
                                @error('duration_days')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- حقل المدة المخصصة -->
                        <div id="custom_duration" style="display: {{ !in_array($membershipType->duration_days, [7, 30, 90, 180, 365]) ? 'block' : 'none' }};">
                            <label for="custom_duration_days" class="block text-sm font-medium text-gray-700 mb-2">المدة المخصصة (بالأيام)</label>
                            <input type="number" name="custom_duration_days" id="custom_duration_days" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ !in_array($membershipType->duration_days, [7, 30, 90, 180, 365]) ? $membershipType->duration_days : '' }}" {{ $membershipType->is_protected ? 'readonly' : '' }}>
                        </div>

                        <!-- المميزات -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مميزات العضوية</label>
                            <div id="features-container">
                                @if($membershipType->features && count($membershipType->features) > 0)
                                    @foreach($membershipType->features as $feature)
                                        <div class="feature-item flex items-center space-x-2 mb-2">
                                            <input type="text" name="features[]" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $feature }}" placeholder="أدخل ميزة" {{ $membershipType->is_protected ? 'readonly' : '' }}>
                                            @if(!$membershipType->is_protected)
                                                <button type="button" class="remove-feature bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">حذف</button>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="feature-item flex items-center space-x-2 mb-2">
                                        <input type="text" name="features[]" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="أدخل ميزة" {{ $membershipType->is_protected ? 'readonly' : '' }}>
                                        @if(!$membershipType->is_protected)
                                            <button type="button" class="remove-feature bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600" style="display: none;">حذف</button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @if(!$membershipType->is_protected)
                                <button type="button" id="add-feature" class="mt-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">إضافة ميزة</button>
                            @endif
                        </div>

                        <!-- حالة النشاط -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('is_active', $membershipType->is_active) ? 'checked' : '' }} {{ $membershipType->is_protected ? 'disabled' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">تفعيل نوع العضوية</label>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('membership-types.index') }}" class="text-gray-500 hover:text-gray-700">إلغاء</a>
                            @if(!$membershipType->is_protected)
                                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    تحديث نوع العضوية
                                </button>
                            @else
                                <div class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-500 bg-gray-100 cursor-not-allowed">
                                    🔒 محمي من التعديل
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!$membershipType->is_protected)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // إدارة المدة المخصصة
            const durationSelect = document.getElementById('duration_days');
            const customDurationDiv = document.getElementById('custom_duration');
            const customDurationInput = document.getElementById('custom_duration_days');

            durationSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDurationDiv.style.display = 'block';
                    customDurationInput.required = true;
                } else {
                    customDurationDiv.style.display = 'none';
                    customDurationInput.required = false;
                }
            });

            // إدارة المميزات
            const featuresContainer = document.getElementById('features-container');
            const addFeatureBtn = document.getElementById('add-feature');

            if (addFeatureBtn) {
                addFeatureBtn.addEventListener('click', function() {
                    const featureItem = document.createElement('div');
                    featureItem.className = 'feature-item flex items-center space-x-2 mb-2';
                    featureItem.innerHTML = `
                        <input type="text" name="features[]" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="أدخل ميزة">
                        <button type="button" class="remove-feature bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">حذف</button>
                    `;
                    featuresContainer.appendChild(featureItem);
                    updateRemoveButtons();
                });
            }

            featuresContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-feature')) {
                    e.target.parentElement.remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const featureItems = featuresContainer.querySelectorAll('.feature-item');
                featureItems.forEach((item, index) => {
                    const removeBtn = item.querySelector('.remove-feature');
                    if (removeBtn && featureItems.length > 1) {
                        removeBtn.style.display = 'block';
                    } else if (removeBtn) {
                        removeBtn.style.display = 'none';
                    }
                });
            }

            // تحديث أزرار الحذف عند التحميل
            updateRemoveButtons();
        });
    </script>
    @endif
</x-app-layout>