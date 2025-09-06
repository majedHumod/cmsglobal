@extends('layouts.admin')

@section('title', 'تعديل نوع العضوية')

@section('header', 'تعديل نوع العضوية: ' . $membershipType->name)

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('membership-types.show', $membershipType) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        عرض التفاصيل
    </a>
    <a href="{{ route('membership-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
            <h2 class="text-lg font-medium text-gray-900">تعديل نوع العضوية</h2>
            <p class="mt-1 text-sm text-gray-500">قم بتعديل معلومات ومميزات نوع العضوية.</p>
        </div>

        @if($membershipType->is_protected)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <strong class="font-bold">تحذير!</strong>
                        <span class="block sm:inline">هذا النوع من العضوية محمي من النظام ولا يمكن تعديله.</span>
                    </div>
                </div>
            </div>
        @endif

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

        <form action="{{ route('membership-types.update', $membershipType) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- معلومات العضوية الأساسية -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900">معلومات العضوية الأساسية</h3>
                <p class="mt-1 text-sm text-gray-500">تعديل المعلومات الأساسية لنوع العضوية.</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اسم العضوية -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">اسم نوع العضوية *</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('name', $membershipType->name) }}" required {{ $membershipType->is_protected ? 'readonly' : '' }} placeholder="مثال: عضوية ذهبية">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($membershipType->is_protected)
                            <p class="mt-1 text-sm text-red-500">🔒 هذا الحقل محمي ولا يمكن تعديله.</p>
                        @else
                            <p class="mt-1 text-sm text-gray-500">اختر اسماً واضحاً ومميزاً لنوع العضوية.</p>
                        @endif
                    </div>

                    <!-- ترتيب العرض -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">ترتيب العرض</label>
                        <input type="number" name="sort_order" id="sort_order" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('sort_order', $membershipType->sort_order) }}" {{ $membershipType->is_protected ? 'readonly' : '' }}>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">الرقم الأصغر يظهر أولاً في القائمة.</p>
                    </div>
                </div>

                <!-- وصف العضوية -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">وصف العضوية</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="وصف مختصر لنوع العضوية ومميزاتها" {{ $membershipType->is_protected ? 'readonly' : '' }}>{{ old('description', $membershipType->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">وصف قصير يوضح مميزات هذا النوع من العضوية.</p>
                </div>
            </div>

            <!-- إعدادات التسعير والمدة -->
            <div class="border-b border-gray-200 py-6">
                <h3 class="text-lg font-medium text-gray-900">إعدادات التسعير والمدة</h3>
                <p class="mt-1 text-sm text-gray-500">تعديل سعر الاشتراك ومدة العضوية.</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- السعر -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">سعر الاشتراك (ريال) *</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="price" id="price" step="0.01" min="0" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('price', $membershipType->price) }}" required {{ $membershipType->is_protected ? 'readonly' : '' }}>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">ريال</span>
                            </div>
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">ضع 0 للعضوية المجانية</p>
                    </div>

                    <!-- مدة الاشتراك -->
                    <div>
                        <label for="duration_days" class="block text-sm font-medium text-gray-700">مدة الاشتراك *</label>
                        <select name="duration_days" id="duration_days" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required {{ $membershipType->is_protected ? 'disabled' : '' }}>
                            <option value="7" {{ old('duration_days', $membershipType->duration_days) == 7 ? 'selected' : '' }}>أسبوع واحد (7 أيام)</option>
                            <option value="30" {{ old('duration_days', $membershipType->duration_days) == 30 ? 'selected' : '' }}>شهر واحد (30 يوم)</option>
                            <option value="90" {{ old('duration_days', $membershipType->duration_days) == 90 ? 'selected' : '' }}>3 أشهر (90 يوم)</option>
                            <option value="180" {{ old('duration_days', $membershipType->duration_days) == 180 ? 'selected' : '' }}>6 أشهر (180 يوم)</option>
                            <option value="365" {{ old('duration_days', $membershipType->duration_days) == 365 ? 'selected' : '' }}>سنة واحدة (365 يوم)</option>
                            <option value="custom" {{ !in_array($membershipType->duration_days, [7, 30, 90, 180, 365]) ? 'selected' : '' }}>مدة مخصصة</option>
                        </select>
                        @error('duration_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- حقل المدة المخصصة -->
                <div id="custom_duration" class="mt-6" style="display: {{ !in_array($membershipType->duration_days, [7, 30, 90, 180, 365]) ? 'block' : 'none' }};">
                    <label for="custom_duration_days" class="block text-sm font-medium text-gray-700">المدة المخصصة (بالأيام)</label>
                    <input type="number" name="custom_duration_days" id="custom_duration_days" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ !in_array($membershipType->duration_days, [7, 30, 90, 180, 365]) ? $membershipType->duration_days : '' }}" {{ $membershipType->is_protected ? 'readonly' : '' }} placeholder="أدخل عدد الأيام">
                    <p class="mt-1 text-sm text-gray-500">أدخل عدد الأيام المطلوب للاشتراك.</p>
                </div>
            </div>

            <!-- مميزات العضوية -->
            <div class="border-b border-gray-200 py-6">
                <h3 class="text-lg font-medium text-gray-900">مميزات العضوية</h3>
                <p class="mt-1 text-sm text-gray-500">تعديل المميزات التي يحصل عليها المشترك في هذا النوع من العضوية.</p>
                
                <div class="mt-6">
                    <div id="features-container">
                        @if($membershipType->features && count($membershipType->features) > 0)
                            @foreach($membershipType->features as $feature)
                                <div class="feature-item flex items-center space-x-2 mb-3">
                                    <div class="flex-1">
                                        <input type="text" name="features[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $feature }}" placeholder="أدخل ميزة من مميزات العضوية" {{ $membershipType->is_protected ? 'readonly' : '' }}>
                                    </div>
                                    @if(!$membershipType->is_protected)
                                        <button type="button" class="remove-feature bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="feature-item flex items-center space-x-2 mb-3">
                                <div class="flex-1">
                                    <input type="text" name="features[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="أدخل ميزة من مميزات العضوية" {{ $membershipType->is_protected ? 'readonly' : '' }}>
                                </div>
                                @if(!$membershipType->is_protected)
                                    <button type="button" class="remove-feature bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition-colors" style="display: none;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    @if(!$membershipType->is_protected)
                        <button type="button" id="add-feature" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            إضافة ميزة
                        </button>
                        
                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">نصائح لتعديل المميزات</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>تأكد من دقة المميزات المذكورة</li>
                                            <li>احذف المميزات غير المتاحة</li>
                                            <li>أضف مميزات جديدة إذا لزم الأمر</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- إعدادات التسعير والمدة -->
            <div class="border-b border-gray-200 py-6">
                <h3 class="text-lg font-medium text-gray-900">إعدادات التسعير والمدة</h3>
                <p class="mt-1 text-sm text-gray-500">تعديل سعر الاشتراك ومدة العضوية.</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- السعر -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">سعر الاشتراك (ريال) *</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="price" id="price" step="0.01" min="0" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('price', $membershipType->price) }}" required {{ $membershipType->is_protected ? 'readonly' : '' }}>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">ريال</span>
                            </div>
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">ضع 0 للعضوية المجانية</p>
                    </div>

                    <!-- مدة الاشتراك -->
                    <div>
                        <label for="duration_days" class="block text-sm font-medium text-gray-700">مدة الاشتراك *</label>
                        <select name="duration_days" id="duration_days" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required {{ $membershipType->is_protected ? 'disabled' : '' }}>
                            <option value="7" {{ old('duration_days', $membershipType->duration_days) == 7 ? 'selected' : '' }}>أسبوع واحد (7 أيام)</option>
                            <option value="30" {{ old('duration_days', $membershipType->duration_days) == 30 ? 'selected' : '' }}>شهر واحد (30 يوم)</option>
                            <option value="90" {{ old('duration_days', $membershipType->duration_days) == 90 ? 'selected' : '' }}>3 أشهر (90 يوم)</option>
                            <option value="180" {{ old('duration_days', $membershipType->duration_days) == 180 ? 'selected' : '' }}>6 أشهر (180 يوم)</option>
                            <option value="365" {{ old('duration_days', $membershipType->duration_days) == 365 ? 'selected' : '' }}>سنة واحدة (365 يوم)</option>
                            <option value="custom" {{ !in_array($membershipType->duration_days, [7, 30, 90, 180, 365]) ? 'selected' : '' }}>مدة مخصصة</option>
                        </select>
                        @error('duration_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- حقل المدة المخصصة -->
                <div id="custom_duration" class="mt-6" style="display: {{ !in_array($membershipType->duration_days, [7, 30, 90, 180, 365]) ? 'block' : 'none' }};">
                    <label for="custom_duration_days" class="block text-sm font-medium text-gray-700">المدة المخصصة (بالأيام)</label>
                    <input type="number" name="custom_duration_days" id="custom_duration_days" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ !in_array($membershipType->duration_days, [7, 30, 90, 180, 365]) ? $membershipType->duration_days : '' }}" {{ $membershipType->is_protected ? 'readonly' : '' }} placeholder="أدخل عدد الأيام">
                    <p class="mt-1 text-sm text-gray-500">أدخل عدد الأيام المطلوب للاشتراك.</p>
                </div>
            </div>

            <!-- إعدادات النشر -->
            <div class="py-6">
                <h3 class="text-lg font-medium text-gray-900">إعدادات النشر</h3>
                <p class="mt-1 text-sm text-gray-500">تحديد حالة نشر نوع العضوية.</p>
                
                <div class="mt-6">
                    <!-- حالة النشاط -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_active" id="is_active" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('is_active', $membershipType->is_active) ? 'checked' : '' }} {{ $membershipType->is_protected ? 'disabled' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-700">تفعيل نوع العضوية</label>
                            <p class="text-gray-500">عند التفعيل، سيكون هذا النوع من العضوية متاحاً للمستخدمين للاشتراك فيه.</p>
                        </div>
                    </div>
                </div>
                
                <!-- معلومات العضوية -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">معلومات العضوية</h4>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membershipType->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">آخر تحديث</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membershipType->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">الرمز المميز</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membershipType->slug }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">عدد المشتركين النشطين</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membershipType->getActiveSubscribersCount() }} مشترك</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">محمي من النظام</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membershipType->is_protected ? '🔒 نعم' : '✅ لا' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">إجمالي الاشتراكات</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membershipType->userMemberships->count() }} اشتراك</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('membership-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    إلغاء
                </a>
                @if(!$membershipType->is_protected)
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        تحديث نوع العضوية
                    </button>
                @else
                    <div class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-gray-100 cursor-not-allowed">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        🔒 محمي من التعديل
                    </div>
                @endif
            </div>
        </form>
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
                featureItem.className = 'feature-item flex items-center space-x-2 mb-3';
                featureItem.innerHTML = `
                    <div class="flex-1">
                        <input type="text" name="features[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="أدخل ميزة من مميزات العضوية">
                    </div>
                    <button type="button" class="remove-feature bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                `;
                featuresContainer.appendChild(featureItem);
                updateRemoveButtons();
            });
        }

        featuresContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-feature')) {
                e.target.closest('.feature-item').remove();
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
@endsection