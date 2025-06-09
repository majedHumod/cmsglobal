<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة الجداول الغذائية') }}
                @if(auth()->user()->hasRole('admin'))
                    <span class="text-sm text-gray-500 ml-2">(عرض جميع الوجبات كمدير)</span>
                @endif
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('meal-plans.public') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    عرض الجداول العامة
                </a>
                <a href="{{ route('meal-plans.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    إضافة وجبة جديدة
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if($mealPlans->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد وجبات</h3>
                            <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء وجبة جديدة.</p>
                            <div class="mt-6">
                                <a href="{{ route('meal-plans.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    إضافة وجبة جديدة
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($mealPlans as $mealPlan)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
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
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $mealPlan->name }}</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mealPlan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $mealPlan->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </div>
                                        
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
                                            بواسطة: {{ $mealPlan->user->name }} | {{ $mealPlan->created_at->format('d/m/Y') }}
                                        </p>
                                        
                                        <div class="flex space-x-2">
                                            <a href="{{ route('meal-plans.show', $mealPlan) }}" class="flex-1 text-center bg-blue-500 hover:bg-blue-700 text-white text-sm py-2 px-3 rounded">
                                                عرض
                                            </a>
                                            @if(auth()->user()->hasRole('admin') || $mealPlan->user_id === auth()->id())
                                                <a href="{{ route('meal-plans.edit', $mealPlan) }}" class="flex-1 text-center bg-yellow-500 hover:bg-yellow-700 text-white text-sm py-2 px-3 rounded">
                                                    تعديل
                                                </a>
                                                <form action="{{ route('meal-plans.destroy', $mealPlan) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white text-sm py-2 px-3 rounded" onclick="return confirm('هل أنت متأكد من حذف هذه الوجبة؟')">
                                                        حذف
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>