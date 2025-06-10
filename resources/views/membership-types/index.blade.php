<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة أنواع العضويات') }}
            </h2>
            <a href="{{ route('membership-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                إضافة نوع عضوية جديد
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if($membershipTypes->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد أنواع عضويات</h3>
                            <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء نوع عضوية جديد.</p>
                            <div class="mt-6">
                                <a href="{{ route('membership-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    إضافة نوع عضوية جديد
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($membershipTypes as $membershipType)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden {{ $membershipType->is_protected ? 'ring-2 ring-red-200' : '' }}">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $membershipType->name }}</h3>
                                            {!! $membershipType->status_badge !!}
                                        </div>
                                        
                                        @if($membershipType->description)
                                            <p class="text-gray-600 text-sm mb-4">{{ $membershipType->description }}</p>
                                        @endif
                                        
                                        <div class="mb-4">
                                            <div class="text-2xl font-bold text-indigo-600">{{ $membershipType->formatted_price }}</div>
                                            <div class="text-sm text-gray-500">{{ $membershipType->duration_text }}</div>
                                        </div>
                                        
                                        @if($membershipType->features)
                                            <div class="mb-4">
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">المميزات:</h4>
                                                <ul class="text-sm text-gray-600 space-y-1">
                                                    @foreach($membershipType->features as $feature)
                                                        <li class="flex items-center">
                                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            {{ $feature }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        <div class="text-sm text-gray-500 mb-4">
                                            المشتركين النشطين: {{ $membershipType->getActiveSubscribersCount() }}
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <a href="{{ route('membership-types.show', $membershipType) }}" class="flex-1 text-center bg-blue-500 hover:bg-blue-700 text-white text-sm py-2 px-3 rounded">
                                                عرض
                                            </a>
                                            
                                            @if($membershipType->canBeModified())
                                                <a href="{{ route('membership-types.edit', $membershipType) }}" class="flex-1 text-center bg-yellow-500 hover:bg-yellow-700 text-white text-sm py-2 px-3 rounded">
                                                    تعديل
                                                </a>
                                                
                                                <form action="{{ route('membership-types.toggle-status', $membershipType) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full bg-gray-500 hover:bg-gray-700 text-white text-sm py-2 px-3 rounded">
                                                        {{ $membershipType->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
                                                    </button>
                                                </form>
                                            @else
                                                <div class="flex-1 text-center bg-gray-300 text-gray-500 text-sm py-2 px-3 rounded">
                                                    🔒 محمي
                                                </div>
                                            @endif
                                            
                                            @if($membershipType->canBeDeleted())
                                                <form action="{{ route('membership-types.destroy', $membershipType) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white text-sm py-2 px-3 rounded" onclick="return confirm('هل أنت متأكد من حذف هذا النوع من العضوية؟')">
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