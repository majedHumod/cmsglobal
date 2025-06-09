<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('الجداول الغذائية') }}
            </h2>
            <a href="{{ route('meal-plans.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                إدارة وجباتي
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- فلاتر البحث -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="meal_type" class="block text-sm font-medium text-gray-700 mb-1">نوع الوجبة</label>
                        <select name="meal_type" id="meal_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">جميع الأنواع</option>
                            <option value="breakfast" {{ request('meal_type') == 'breakfast' ? 'selected' : '' }}>إفطار</option>
                            <option value="lunch" {{ request('meal_type') == 'lunch' ? 'selected' : '' }}>غداء</option>
                            <option value="dinner" {{ request('meal_type') == 'dinner' ? 'selected' : '' }}>عشاء</option>
                            <option value="snack" {{ request('meal_type') == 'snack' ? 'selected' : '' }}>وجبة خفيفة</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">مستوى الصعوبة</label>
                        <select name="difficulty" id="difficulty" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">جميع المستويات</option>
                            <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>سهل</option>
                            <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>متوسط</option>
                            <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>صعب</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ابحث في الوجبات..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md">
                            بحث
                        </button>
                    </div>
                </form>
            </div>

            @if($mealPlans->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد وجبات</h3>
                    <p class="mt-1 text-sm text-gray-500">لم يتم العثور على وجبات تطابق معايير البحث.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($mealPlans as $mealPlan)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            @if($mealPlan->image)
                                <img src="{{ Storage::url($mealPlan->image) }}" alt="{{ $mealPlan->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $mealPlan->name }}</h3>
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-500 mb-2">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ $mealPlan->meal_type_name }}</span>
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">{{ $mealPlan->difficulty_name }}</span>
                                </div>
                                
                                @if($mealPlan->description)
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($mealPlan->description, 100) }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                    @if($mealPlan->calories)
                                        <span>{{ $mealPlan->calories }} سعرة</span>
                                    @endif
                                    @if($mealPlan->total_time > 0)
                                        <span>{{ $mealPlan->total_time }} دقيقة</span>
                                    @endif
                                    <span>{{ $mealPlan->servings }} حصة</span>
                                </div>
                                
                                <p class="text-xs text-gray-400 mb-3">
                                    بواسطة: {{ $mealPlan->user->name }}
                                </p>
                                
                                <a href="{{ route('meal-plans.show', $mealPlan) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                    عرض الوصفة
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $mealPlans->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>