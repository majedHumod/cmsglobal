<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $mealPlan->name }}
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->hasRole('admin') || $mealPlan->user_id === auth()->id())
                    <a href="{{ route('meal-plans.edit', $mealPlan) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        تعديل
                    </a>
                @endif
                <a href="{{ route('meal-plans.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    العودة للقائمة
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- صورة الوجبة -->
                @if($mealPlan->image)
                    <div class="w-full h-64 md:h-80">
                        <img src="{{ Storage::url($mealPlan->image) }}" alt="{{ $mealPlan->name }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6">
                    <!-- معلومات أساسية -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $mealPlan->name }}</h1>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $mealPlan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $mealPlan->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>

                        @if($mealPlan->description)
                            <p class="text-gray-600 text-lg mb-4">{{ $mealPlan->description }}</p>
                        @endif

                        <!-- معلومات سريعة -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
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
                    </div>

                    <!-- المكونات -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">المكونات</h2>
                        <div class="bg-gray-50 p-4 rounded-lg">
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

                    <!-- طريقة التحضير -->
                    @if($mealPlan->instructions)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">طريقة التحضير</h2>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="prose max-w-none">
                                    {!! nl2br(e($mealPlan->instructions)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- معلومات إضافية -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>أنشأ بواسطة: {{ $mealPlan->user->name }}</span>
                            <span>{{ $mealPlan->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($mealPlan->updated_at != $mealPlan->created_at)
                            <div class="text-sm text-gray-500 mt-1">
                                آخر تحديث: {{ $mealPlan->updated_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>