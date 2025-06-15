<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة الصفحات') }}
                @if(auth()->user()->hasRole('admin'))
                    <span class="text-sm text-gray-500 ml-2">(عرض جميع الصفحات كمدير)</span>
                @endif
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('pages.public') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    عرض الصفحات العامة
                </a>
                <a href="{{ route('pages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    إضافة صفحة جديدة
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
                    @if($pages->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد صفحات</h3>
                            <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء صفحة جديدة.</p>
                            <div class="mt-6">
                                <a href="{{ route('pages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    إضافة صفحة جديدة
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">العنوان</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الوصول</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المؤلف</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ النشر</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pages as $page)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $page->title }}</div>
                                                        <div class="text-sm text-gray-500">{{ $page->slug }}</div>
                                                        @if($page->excerpt)
                                                            <div class="text-xs text-gray-400 mt-1">{{ Str::limit($page->excerpt, 50) }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col space-y-1">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $page->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $page->is_published ? 'منشور' : 'مسودة' }}
                                                    </span>
                                                    @if($page->show_in_menu)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            في القائمة
                                                        </span>
                                                    @endif
                                                    @if($page->is_premium)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            💎 مدفوع
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <span class="text-lg mr-1">{{ $page->access_level_icon }}</span>
                                                    <span class="text-sm text-gray-900">{{ $page->access_level_text }}</span>
                                                    @if($page->access_level === 'membership')
                                                        <span class="ml-1 text-xs text-gray-500">
                                                            @php
                                                                $membershipCount = 0;
                                                                if ($page->required_membership_types) {
                                                                    $membershipTypes = is_array($page->required_membership_types) ? 
                                                                        $page->required_membership_types : 
                                                                        json_decode($page->required_membership_types, true);
                                                                    $membershipCount = is_array($membershipTypes) ? count($membershipTypes) : 0;
                                                                }
                                                            @endphp
                                                            ({{ $membershipCount }} عضوية)
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $page->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($page->published_at)
                                                    {{ $page->published_at->format('d/m/Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    @if($page->is_published)
                                                        <a href="{{ route('pages.show', $page->slug) }}" class="text-blue-600 hover:text-blue-900" target="_blank">عرض</a>
                                                    @endif
                                                    @if(auth()->user()->hasRole('admin') || $page->user_id === auth()->id())
                                                        <a href="{{ route('pages.edit', $page) }}" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                                        <form action="{{ route('pages.destroy', $page) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذه الصفحة؟')">
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>