@extends('layouts.admin')

@section('title', 'الجدول الأسبوعي للتمارين')

@section('header', 'الجدول الأسبوعي للتمارين')

@section('header_actions')
<div class="flex space-x-2">
    @hasanyrole('admin|coach')
        <a href="{{ route('workout-schedules.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            إضافة جدولة جديدة
        </a>
    @endhasanyrole
    <a href="{{ route('workouts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        إدارة التمارين
    </a>
    <a href="{{ route('workout-schedules.weekly') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm0 0v4a2 2 0 002 2h6a2 2 0 002-2v-4"></path>
        </svg>
        العرض الأسبوعي
    </a>
</div>
@endsection

@section('content')
<!-- فلاتر البحث -->
<div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">البحث والفلترة</h3>
        
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- فلترة حسب الأسبوع -->
            <div>
                <label for="week" class="block text-sm font-medium text-gray-700 mb-2">الأسبوع</label>
                <select name="week" id="week" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">جميع الأسابيع</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('week') == $i ? 'selected' : '' }}>الأسبوع {{ $i }}</option>
                    @endfor
                </select>
            </div>
            
            <!-- فلترة حسب التمرين -->
            <div>
                <label for="workout_id" class="block text-sm font-medium text-gray-700 mb-2">التمرين</label>
                <select name="workout_id" id="workout_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">جميع التمارين</option>
                    @foreach($workouts as $workout)
                        <option value="{{ $workout->id }}" {{ request('workout_id') == $workout->id ? 'selected' : '' }}>
                            {{ $workout->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- زر البحث -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                    بحث
                </button>
            </div>
        </form>
    </div>
</div>

<!-- قائمة الجدولة -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900">قائمة جدولة التمارين</h2>
            <p class="mt-1 text-sm text-gray-500">إدارة وتنظيم جدولة التمارين الأسبوعية.</p>
        </div>

        @if($schedules->isEmpty())
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm0 0v4a2 2 0 002 2h6a2 2 0 002-2v-4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد جدولة</h3>
                <p class="text-sm text-gray-500 mb-6">ابدأ بإنشاء جدولة أسبوعية للتمارين.</p>
                @hasanyrole('admin|coach')
                    <a href="{{ route('workout-schedules.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        إضافة جدولة جديدة
                    </a>
                @endhasanyrole
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التمرين</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الأسبوع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الجلسة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المدة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصعوبة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($schedules as $schedule)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->workout->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($schedule->workout->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $schedule->week_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $schedule->session_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $schedule->workout->duration }} دقيقة
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $schedule->workout->difficulty_color }}-100 text-{{ $schedule->workout->difficulty_color }}-800">
                                        {{ $schedule->workout->difficulty_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $schedule->status_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('workout-schedules.show', $schedule) }}" class="text-blue-600 hover:text-blue-900">عرض</a>
                                        @if($schedule->canEdit(auth()->user()))
                                            <a href="{{ route('workout-schedules.edit', $schedule) }}" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                        @endif
                                        @if($schedule->canDelete(auth()->user()))
                                            <form action="{{ route('workout-schedules.destroy', $schedule) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذه الجدولة؟')">
                                                    حذف
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $schedules->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection