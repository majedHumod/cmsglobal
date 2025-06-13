@extends('layouts.admin')

@section('title', 'تقارير الصلاحيات')

@section('breadcrumbs')
<li class="flex items-center">
    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
    <a href="{{ route('admin.permissions.index') }}" class="text-gray-700 hover:text-indigo-600 text-sm font-medium">
        الصلاحيات
    </a>
</li>
<li class="flex items-center">
    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
    <span class="text-gray-400 text-sm font-medium">
        التقارير
    </span>
</li>
@endsection

@section('header', 'تقارير الصلاحيات')

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        العودة للصلاحيات
    </a>
</div>
@endsection

@section('content')
<!-- أنواع التقارير -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.permissions.report', ['type' => 'overview']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'overview' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
            <svg class="-ml-1 mr-2 h-5 w-5 {{ $type == 'overview' ? 'text-indigo-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
            </svg>
            نظرة عامة
        </a>
        <a href="{{ route('admin.permissions.report', ['type' => 'users']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'users' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
            <svg class="-ml-1 mr-2 h-5 w-5 {{ $type == 'users' ? 'text-indigo-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
            </svg>
            المستخدمين
        </a>
        <a href="{{ route('admin.permissions.report', ['type' => 'roles']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'roles' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
            <svg class="-ml-1 mr-2 h-5 w-5 {{ $type == 'roles' ? 'text-indigo-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
            </svg>
            الأدوار
        </a>
        <a href="{{ route('admin.permissions.report', ['type' => 'overrides']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'overrides' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
            <svg class="-ml-1 mr-2 h-5 w-5 {{ $type == 'overrides' ? 'text-indigo-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
            </svg>
            التجاوزات
        </a>
        <a href="{{ route('admin.permissions.report', ['type' => 'audit']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'audit' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
            <svg class="-ml-1 mr-2 h-5 w-5 {{ $type == 'audit' ? 'text-indigo-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
            سجل التدقيق
        </a>
    </div>
</div>

<!-- محتوى التقرير -->
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6">
        @if($type == 'overview')
            <!-- نظرة عامة -->
            <h3 class="text-lg font-medium text-gray-900 mb-6">نظرة عامة على الصلاحيات</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-indigo-50 rounded-lg p-4">
                    <h4 class="font-medium text-indigo-800 mb-4">الصلاحيات</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">إجمالي الصلاحيات:</span>
                            <span class="font-medium">{{ $data['total_permissions'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">الصلاحيات النشطة:</span>
                            <span class="font-medium">{{ $data['active_permissions'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">مجموعات الصلاحيات:</span>
                            <span class="font-medium">{{ $data['permission_groups'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">تصنيفات الصلاحيات:</span>
                            <span class="font-medium">{{ $data['permission_categories'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="font-medium text-green-800 mb-4">الأدوار</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">إجمالي الأدوار:</span>
                            <span class="font-medium">{{ $data['total_roles'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">الأدوار النشطة:</span>
                            <span class="font-medium">{{ $data['active_roles'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 rounded-lg p-4">
                    <h4 class="font-medium text-yellow-800 mb-4">التجاوزات</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">إجمالي التجاوزات:</span>
                            <span class="font-medium">{{ $data['total_overrides'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">التجاوزات النشطة:</span>
                            <span class="font-medium">{{ $data['active_overrides'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">التجاوزات المنتهية:</span>
                            <span class="font-medium">{{ $data['expired_overrides'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">التغييرات الأخيرة:</span>
                            <span class="font-medium">{{ $data['recent_changes'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-6">
                <h4 class="font-medium text-gray-900 mb-4">إجراءات سريعة</h4>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.permissions.cleanup-expired') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        تنظيف التجاوزات المنتهية
                    </a>
                    <a href="{{ route('admin.permissions.groups') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        إدارة المجموعات
                    </a>
                </div>
            </div>
            
        @elseif($type == 'users')
            <!-- تقرير المستخدمين -->
            <h3 class="text-lg font-medium text-gray-900 mb-6">تقرير صلاحيات المستخدمين</h3>
            
            @if(isset($data['users']) && $data['users']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الأدوار</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحيات</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data['users'] as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($user->roles as $role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="text-gray-400 text-sm">لا توجد أدوار</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->permissions_count ?? $user->getAllPermissions()->count() }} صلاحية
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.permissions.manage-user', $user) }}" class="text-indigo-600 hover:text-indigo-900">إدارة الصلاحيات</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($data['users'], 'links'))
                    <div class="mt-4">
                        {{ $data['users']->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">لا توجد بيانات متاحة</p>
                </div>
            @endif
            
        @elseif($type == 'roles')
            <!-- تقرير الأدوار -->
            <h3 class="text-lg font-medium text-gray-900 mb-6">تقرير الأدوار والصلاحيات</h3>
            
            @if(isset($data['roles']) && $data['roles']->count() > 0)
                <div class="space-y-6">
                    @foreach($data['roles'] as $role)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $role->name }}</h3>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $role->users_count ?? 0 }} مستخدم
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $role->permissions_count ?? $role->permissions->count() }} صلاحية
                                        </span>
                                    </div>
                                </div>
                                @if(isset($role->description) && $role->description)
                                    <p class="mt-1 text-sm text-gray-500">{{ $role->description }}</p>
                                @endif
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">الصلاحيات:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($role->permissions as $permission)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400">لا توجد صلاحيات</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">لا توجد بيانات متاحة</p>
                </div>
            @endif
            
        @elseif($type == 'overrides')
            <!-- تقرير التجاوزات -->
            <h3 class="text-lg font-medium text-gray-900 mb-6">تقرير تجاوزات الصلاحيات</h3>
            
            @if(isset($data['overrides']) && $data['overrides']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحية</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الانتهاء</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">بواسطة</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data['overrides'] as $override)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $override->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=Unknown&color=7F9CF5&background=EBF4FF' }}" alt="{{ $override->user->name ?? 'غير معروف' }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $override->user->name ?? 'غير معروف' }}</div>
                                                <div class="text-xs text-gray-500">{{ $override->user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $override->permission->name ?? 'غير معروف' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $override->type == 'grant' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $override->type == 'grant' ? 'منح' : 'منع' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {!! $override->status_badge ?? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">غير معروف</span>' !!}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $override->expires_at ? $override->expires_at->format('Y-m-d H:i') : 'دائم' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $override->grantedBy->name ?? 'غير معروف' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($data['overrides'], 'links'))
                    <div class="mt-4">
                        {{ $data['overrides']->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">لا توجد تجاوزات صلاحيات مسجلة</p>
                </div>
            @endif
            
        @elseif($type == 'audit')
            <!-- سجل التدقيق -->
            <h3 class="text-lg font-medium text-gray-900 mb-6">سجل تدقيق الصلاحيات</h3>
            
            @if(isset($data['logs']) && $data['logs']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراء</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحية</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">بواسطة</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السبب</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data['logs'] as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $log->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $log->action == 'granted' ? 'bg-green-100 text-green-800' : 
                                               ($log->action == 'revoked' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $log->action_text ?? $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->permission_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->auditable->name ?? 'غير معروف' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $log->user->name ?? 'غير معروف' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $log->reason ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($data['logs'], 'links'))
                    <div class="mt-4">
                        {{ $data['logs']->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">لا توجد سجلات تدقيق متاحة</p>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection