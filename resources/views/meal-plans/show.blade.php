@extends('layouts.admin')

@section('title', 'تفاصيل الوجبة')

@section('header', 'تفاصيل الوجبة: ' . $mealPlan->name)

@section('header_actions')
<div class="flex space-x-2">
    @if(auth()->user()->hasRole('admin') || $mealPlan->user_id === auth()->id())
        <a href="{{ route('meal-plans.edit', $mealPlan) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            تعديل
        </a>
    @endif
    <a href="{{ route('meal-plans.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        العودة للقائمة
    </a>
</div>
@endsection

@section('content')
<!-- معلومات الوجبة الأساسية -->
<div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $mealPlan->name }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $mealPlan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $mealPlan->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
            </div>
        </div>

        @if($mealPlan->description)
            <p class="text-gray-600 text-lg mb-6">{{ $mealPlan->description }}</p>
        @endif

        <!-- معلومات سريعة -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $mealPlan->meal_type_name }}</div>
                <div class="text-sm text-gray-600">نوع الوجبة</div>
            </div>
            
            @if($mealPlan->calories)
                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $mealPlan->calories }}</div>
                    <div class="text-sm text-gray-600">سعرة حرارية</div>
                </div>
            @endif
            
            @if($mealPlan->total_time > 0)
                <div class="bg-yellow-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $mealPlan->total_time }}</div>
                    <div class="text-sm text-gray-600">دقيقة</div>
                </div>
            @endif
            
            <div class="bg-purple-50 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $mealPlan->servings }}</div>
                <div class="text-sm text-gray-600">حصة</div>
            </div>
        </div>

        <!-- تفاصيل إضافية -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="flex items-center space-x-2">
                <span class="font-medium text-gray-700">مستوى الصعوبة:</span>
                <span class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $mealPlan->difficulty_name }}</span>
            </div>
            
            @if($mealPlan->prep_time)
                <div class="flex items-center space-x-2">
                    <span class="font-medium text-gray-700">وقت التحضير:</span>
                    <span class="text-gray-600">{{ $mealPlan->prep_time }} دقيقة</span>
                </div>
            @endif
            
            @if($mealPlan->cook_time)
                <div class="flex items-center space-x-2">
                    <span class="font-medium text-gray-700">وقت الطبخ:</span>
                    <span class="text-gray-600">{{ $mealPlan->cook_time }} دقيقة</span>
                </div>
            @endif
        </div>

        <!-- صورة الوجبة -->
        @if($mealPlan->image)
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">صورة الوجبة</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <img src="{{ Storage::url($mealPlan->image) }}" alt="{{ $mealPlan->name }}" class="w-full max-w-md mx-auto rounded-lg shadow-sm">
                </div>
            </div>
        @endif
    </div>
</div>

<!-- معلومات تفصيلية -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- المكونات -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">المكونات</h3>
            <p class="mt-1 text-sm text-gray-500">قائمة بجميع المكونات المطلوبة</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <ul class="space-y-2">
                @foreach(explode("\n", $mealPlan->ingredients) as $ingredient)
                    @if(trim($ingredient))
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ trim($ingredient) }}
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    
    <!-- معلومات الوجبة -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">معلومات الوجبة</h3>
            <p class="mt-1 text-sm text-gray-500">تفاصيل تقنية عن الوجبة</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <dl class="space-y-4">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">المؤلف:</dt>
                    <dd class="text-sm text-gray-900">{{ $mealPlan->user->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء:</dt>
                    <dd class="text-sm text-gray-900">{{ $mealPlan->created_at ? $mealPlan->created_at->format('d/m/Y H:i') : '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">آخر تحديث:</dt>
                    <dd class="text-sm text-gray-900">{{ $mealPlan->updated_at ? $mealPlan->updated_at->format('d/m/Y H:i') : '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">يمكن تعديلها:</dt>
                    <dd class="text-sm text-gray-900">{{ (auth()->user()->hasRole('admin') || $mealPlan->user_id === auth()->id()) ? '✅ نعم' : '❌ لا' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- طريقة التحضير -->
@if($mealPlan->instructions)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">طريقة التحضير</h3>
            <p class="mt-1 text-sm text-gray-500">خطوات تحضير الوجبة بالتفصيل</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <div class="prose max-w-none text-right">
                {!! nl2br(e($mealPlan->instructions)) !!}
            </div>
        </div>
    </div>
@else
    <!-- لا توجد تعليمات -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="text-center py-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد تعليمات تحضير</h3>
                <p class="text-sm text-gray-500 mb-6">لم يتم إضافة تعليمات تحضير لهذه الوجبة بعد.</p>
                
                @if(auth()->user()->hasRole('admin') || $mealPlan->user_id === auth()->id())
                    <a href="{{ route('meal-plans.edit', $mealPlan) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        إضافة تعليمات التحضير
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif
@endsection