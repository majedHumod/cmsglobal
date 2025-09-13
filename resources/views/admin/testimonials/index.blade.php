@extends('layouts.admin')

@section('title', 'إدارة قصص النجاح')

@section('header', 'إدارة قصص النجاح')

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        إضافة قصة نجاح جديدة
    </a>
</div>
@endsection

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900">قائمة قصص النجاح</h2>
            <p class="mt-1 text-sm text-gray-500">إدارة وتنظيم قصص النجاح والشهادات من هنا.</p>
        </div>

        @if($testimonials->isEmpty())
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد قصص نجاح</h3>
                <p class="text-sm text-gray-500 mb-6">ابدأ بإضافة قصة نجاح جديدة لعرضها للزوار.</p>
                <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إضافة قصة نجاح جديدة
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">محتوى القصة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصورة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الترتيب</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($testimonials as $testimonial)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $testimonial->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($testimonial->story_content, 100) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($testimonial->image)
                                        <img src="{{ Storage::url($testimonial->image) }}" alt="{{ $testimonial->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <span class="text-gray-400">لا توجد صورة</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $testimonial->sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $testimonial->status_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $testimonial->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                        
                                        <form action="{{ route('admin.testimonials.toggle-visibility', $testimonial) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="{{ $testimonial->is_visible ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                                {{ $testimonial->is_visible ? 'إخفاء' : 'إظهار' }}
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذه القصة؟')">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection