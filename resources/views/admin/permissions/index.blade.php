@extends('layouts.admin')

@section('title', 'إدارة الصلاحيات المتقدمة')

@section('header', 'إدارة الصلاحيات المتقدمة')

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.permissions.report') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"></path>
        </svg>
        التقارير
    </a>
    <a href="{{ route('admin.permissions.groups') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
        </svg>
        إدارة المجموعات
    </a>
</div>
@endsection

@section('content')
<!-- إحصائيات سريعة -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">إجمالي الصلاحيات</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $statistics['total_permissions'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">الأدوار النشطة</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $statistics['active_roles'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">التجاوزات النشطة</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $statistics['active_overrides'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">التغييرات الأخيرة</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $statistics['recent_changes'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مجموعات الصلاحيات -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">مجموعات الصلاحيات</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">عرض الصلاحيات مجمعة حسب الفئات والمجموعات</p>
        </div>
        <a href="{{ route('admin.permissions.groups') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
            إدارة المجموعات
        </a>
    </div>
    <div class="border-t border-gray-200">
        @forelse($permissionGroups as $group)
            <div class="px-4 py-4 border-b border-gray-200 last:border-b-0">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $group->color }}"></div>
                        <h4 class="text-lg font-medium text-gray-900">
                            @if($group->icon)
                                <i class="{{ $group->icon }} mr-2"></i>
                            @endif
                            {{ $group->name }}
                        </h4>
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $group->permissions_count ?? count($group->categories->flatMap->permissions) ?? 0 }} صلاحية
                        </span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $group->categories_count ?? count($group->categories) ?? 0 }} تصنيف</span>
                </div>
                
                @if($group->description)
                    <p class="text-sm text-gray-600 mb-3">{{ $group->description }}</p>
                @endif

                <!-- تصنيفات المجموعة -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($group->categories as $category)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 mb-2">{{ $category->name }}</h5>
                            @if($category->description)
                                <p class="text-xs text-gray-500 mb-2">{{ $category->description }}</p>
                            @endif
                            
                            <!-- صلاحيات التصنيف -->
                            <div class="space-y-1">
                                @foreach($category->permissions as $permission)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            {{ $permission->level === 'critical' ? 'bg-red-100 text-red-800' : 
                                               ($permission->level === 'advanced' ? 'bg-orange-100 text-orange-800' : 
                                               ($permission->level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                            {{ ucfirst($permission->level) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="px-4 py-8 text-center">
                <p class="text-gray-500">لا توجد مجموعات صلاحيات متاحة</p>
            </div>
        @endforelse
    </div>
</div>

<!-- سجل التغييرات الأخيرة -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">سجل التغييرات الأخيرة</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">آخر التغييرات على الصلاحيات والأدوار</p>
        </div>
        <a href="{{ route('admin.permissions.report', ['type' => 'audit']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
            عرض السجل الكامل
        </a>
    </div>
    <div class="border-t border-gray-200">
        @forelse($recentLogs as $log)
            <div class="px-4 py-4 border-b border-gray-200 last:border-b-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $log->action === 'granted' ? 'bg-green-100 text-green-800' : 
                                   ($log->action === 'revoked' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $log->action_text }}
                            </span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $log->permission_name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $log->auditable_type_text }}: {{ $log->auditable->name ?? 'غير محدد' }}
                                @if($log->user)
                                    بواسطة {{ $log->user->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $log->created_at->diffForHumans() }}
                    </div>
                </div>
                @if($log->reason)
                    <div class="mt-2 text-sm text-gray-600">
                        <strong>السبب:</strong> {{ $log->reason }}
                    </div>
                @endif
            </div>
        @empty
            <div class="px-4 py-8 text-center">
                <p class="text-gray-500">لا توجد تغييرات حديثة</p>
            </div>
        @endforelse
    </div>
</div>
@endsection