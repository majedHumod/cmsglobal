@extends('layouts.admin')

@section('title', 'العرض الأسبوعي للتمارين')

@section('header', 'العرض الأسبوعي للتمارين - الأسبوع ' . $weekNumber)

@section('header_actions')
<div class="flex space-x-2">
    <form method="GET" class="flex items-center space-x-2">
        <select name="week" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            @for($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ $weekNumber == $i ? 'selected' : '' }}>الأسبوع {{ $i }}</option>
            @endfor
        </select>
        <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            عرض
        </button>
    </form>
    <a href="{{ route('workout-schedules.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
            <h2 class="text-lg font-medium text-gray-900">الجدول الأسبوعي - الأسبوع {{ $weekNumber }}</h2>
            <p class="mt-1 text-sm text-gray-500">عرض جميع التمارين المجدولة لهذا الأسبوع.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @for($session = 1; $session <= 7; $session++)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">
                        الجلسة {{ $session }}
                        @php
                            $sessionNames = [
                                1 => 'السبت',
                                2 => 'الأحد', 
                                3 => 'الاثنين',
                                4 => 'الثلاثاء',
                                5 => 'الأربعاء',
                                6 => 'الخميس',
                                7 => 'الجمعة'
                            ];
                        @endphp
                        <div class="text-sm text-gray-500 font-normal">{{ $sessionNames[$session] }}</div>
                    </h3>
                    
                    @if(isset($weeklySchedule[$session]) && $weeklySchedule[$session]->count() > 0)
                        <div class="space-y-3">
                            @foreach($weeklySchedule[$session] as $schedule)
                                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                                    <h4 class="font-medium text-gray-900 mb-2">{{ $schedule->workout->name }}</h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <div class="flex justify-between">
                                            <span>المدة:</span>
                                            <span>{{ $schedule->workout->duration }} دقيقة</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>الصعوبة:</span>
                                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-{{ $schedule->workout->difficulty_color }}-100 text-{{ $schedule->workout->difficulty_color }}-800">
                                                {{ $schedule->workout->difficulty_name }}
                                            </span>
                                        </div>
                                        @if($schedule->notes)
                                            <div class="mt-2 p-2 bg-blue-50 rounded text-xs">
                                                <strong>ملاحظة:</strong> {{ Str::limit($schedule->notes, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($schedule->canEdit(auth()->user()))
                                        <div class="mt-3 flex space-x-2">
                                            <a href="{{ route('workout-schedules.edit', $schedule) }}" class="text-xs text-indigo-600 hover:text-indigo-900">تعديل</a>
                                            <form action="{{ route('workout-schedules.destroy', $schedule) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذه الجدولة؟')">
                                                    حذف
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <p class="text-sm text-gray-500">لا توجد تمارين</p>
                            @hasanyrole('admin|coach')
                                <a href="{{ route('workout-schedules.create', ['week' => $weekNumber, 'session' => $session]) }}" class="mt-2 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                    إضافة تمرين
                                </a>
                            @endhasanyrole
                        </div>
                    @endif
                </div>
            @endfor
        </div>

        <!-- ملخص الأسبوع -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">ملخص الأسبوع {{ $weekNumber }}</h3>
            
            @php
                $totalWorkouts = collect($weeklySchedule)->flatten()->count();
                $totalDuration = collect($weeklySchedule)->flatten()->sum(function($schedule) {
                    return $schedule->workout->duration;
                });
                $difficulties = collect($weeklySchedule)->flatten()->groupBy(function($schedule) {
                    return $schedule->workout->difficulty;
                });
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-indigo-600">{{ $totalWorkouts }}</div>
                    <div class="text-sm text-gray-600">إجمالي التمارين</div>
                </div>
                
                <div class="bg-white p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $totalDuration }}</div>
                    <div class="text-sm text-gray-600">إجمالي الدقائق</div>
                </div>
                
                <div class="bg-white p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $difficulties->get('easy', collect())->count() }}</div>
                    <div class="text-sm text-gray-600">تمارين سهلة</div>
                </div>
                
                <div class="bg-white p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $difficulties->get('hard', collect())->count() }}</div>
                    <div class="text-sm text-gray-600">تمارين صعبة</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection