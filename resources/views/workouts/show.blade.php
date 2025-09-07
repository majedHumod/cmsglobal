@extends('layouts.admin')

@section('title', 'تفاصيل التمرين')

@section('header', 'تفاصيل التمرين: ' . $workout->name)

@section('header_actions')
<div class="flex space-x-2">
    @if($workout->canEdit(auth()->user()))
        <a href="{{ route('workouts.edit', $workout) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            تعديل
        </a>
    @endif
    <a href="{{ route('workouts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        العودة للقائمة
    </a>
</div>
@endsection

@section('content')
<!-- معلومات التمرين الأساسية -->
<div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $workout->name }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $workout->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $workout->status_name }}
                    </span>
                </div>
            </div>
        </div>

        <p class="text-gray-600 text-lg mb-6">{{ $workout->description }}</p>

        <!-- معلومات سريعة -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $workout->duration }}</div>
                <div class="text-sm text-gray-600">دقيقة</div>
            </div>
            
            <div class="bg-{{ $workout->difficulty_color }}-50 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-{{ $workout->difficulty_color }}-600">{{ $workout->difficulty_name }}</div>
                <div class="text-sm text-gray-600">مستوى الصعوبة</div>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $workout->schedules->count() }}</div>
                <div class="text-sm text-gray-600">جدولة</div>
            </div>
        </div>

        <!-- فيديو التمرين -->
        @if($workout->video_url)
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">فيديو التمرين</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="aspect-w-16 aspect-h-9">
                        @if(str_contains($workout->video_url, 'youtube.com') || str_contains($workout->video_url, 'youtu.be'))
                            @php
                                $videoId = '';
                                if (str_contains($workout->video_url, 'youtube.com/watch?v=')) {
                                    $videoId = substr($workout->video_url, strpos($workout->video_url, 'v=') + 2);
                                    $videoId = substr($videoId, 0, strpos($videoId, '&') ?: strlen($videoId));
                                } elseif (str_contains($workout->video_url, 'youtu.be/')) {
                                    $videoId = substr($workout->video_url, strrpos($workout->video_url, '/') + 1);
                                }
                            @endphp
                            @if($videoId)
                                <iframe class="w-full h-64 rounded" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                            @else
                                <a href="{{ $workout->video_url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                    </svg>
                                    مشاهدة الفيديو
                                </a>
                            @endif
                        @else
                            <a href="{{ $workout->video_url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                </svg>
                                مشاهدة الفيديو
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- معلومات تفصيلية -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- معلومات التمرين -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">معلومات التمرين</h3>
            <p class="mt-1 text-sm text-gray-500">تفاصيل تقنية عن التمرين</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <dl class="space-y-4">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">المدرب:</dt>
                    <dd class="text-sm text-gray-900">{{ $workout->user->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء:</dt>
                    <dd class="text-sm text-gray-900">{{ $workout->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">آخر تحديث:</dt>
                    <dd class="text-sm text-gray-900">{{ $workout->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">يمكن تعديله:</dt>
                    <dd class="text-sm text-gray-900">{{ $workout->canEdit(auth()->user()) ? '✅ نعم' : '❌ لا' }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- إحصائيات الجدولة -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">إحصائيات الجدولة</h3>
            <p class="mt-1 text-sm text-gray-500">بيانات جدولة هذا التمرين</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <dl class="space-y-4">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">إجمالي الجدولات:</dt>
                    <dd class="text-sm text-gray-900">{{ $workout->schedules->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الجدولات النشطة:</dt>
                    <dd class="text-sm text-gray-900">{{ $workout->schedules->where('status', true)->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">عدد الأسابيع:</dt>
                    <dd class="text-sm text-gray-900">{{ $workout->schedules->pluck('week_number')->unique()->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- جدولة التمرين -->
@if($workout->schedules->count() > 0)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">جدولة التمرين</h3>
                    <p class="mt-1 text-sm text-gray-500">الأسابيع والجلسات المجدولة لهذا التمرين</p>
                </div>
                @hasanyrole('admin|coach')
                    <a href="{{ route('workout-schedules.create', ['workout_id' => $workout->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        إضافة جدولة
                    </a>
                @endhasanyrole
            </div>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الأسبوع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الجلسة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الملاحظات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($workout->schedules->take(10) as $schedule)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $schedule->week_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $schedule->session_name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $schedule->notes ? Str::limit($schedule->notes, 50) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $schedule->status_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
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
            
            @if($workout->schedules->count() > 10)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <p class="text-sm text-gray-500 text-center">عرض 10 من أصل {{ $workout->schedules->count() }} جدولة</p>
                    <div class="mt-2 text-center">
                        <a href="{{ route('workout-schedules.index', ['workout_id' => $workout->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                            عرض جميع الجدولات
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    <!-- لا توجد جدولات -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="text-center py-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm0 0v4a2 2 0 002 2h6a2 2 0 002-2v-4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد جدولة</h3>
                <p class="text-sm text-gray-500 mb-6">لم يتم جدولة هذا التمرين في أي أسبوع بعد.</p>
                
                @hasanyrole('admin|coach')
                    <a href="{{ route('workout-schedules.create', ['workout_id' => $workout->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        إضافة جدولة
                    </a>
                @endhasanyrole
            </div>
        </div>
    </div>
@endif
@endsection