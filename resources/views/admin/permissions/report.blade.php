<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 {{ __('تقارير الصلاحيات') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    ← العودة للصلاحيات
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- أنواع التقارير -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 flex flex-wrap gap-2">
                    <a href="{{ route('admin.permissions.report', ['type' => 'overview']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'overview' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
                        نظرة عامة
                    </a>
                    <a href="{{ route('admin.permissions.report', ['type' => 'users']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'users' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
                        المستخدمين
                    </a>
                    <a href="{{ route('admin.permissions.report', ['type' => 'roles']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'roles' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
                        الأدوار
                    </a>
                    <a href="{{ route('admin.permissions.report', ['type' => 'overrides']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'overrides' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
                        التجاوزات
                    </a>
                    <a href="{{ route('admin.permissions.report', ['type' => 'audit']) }}" class="inline-flex items-center px-4 py-2 border {{ $type == 'audit' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm font-medium rounded-md">
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
                                    تنظيف التجاوزات المنتهية
                                </a>
                                <a href="{{ route('admin.permissions.groups') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الأدوار</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحيات</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($data['users'] as $user)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div>
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
                                <p class="text-gray-500">لا توجد بيانات متاحة</p>
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
                                <p class="text-gray-500">لا توجد بيانات متاحة</p>
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحية</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الانتهاء</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">بواسطة</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($data['overrides'] as $override)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $override->user->name ?? 'غير معروف' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $override->user->email ?? '' }}</div>
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
                                <p class="text-gray-500">لا توجد تجاوزات صلاحيات مسجلة</p>
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراء</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحية</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">بواسطة</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">السبب</th>
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
                                <p class="text-gray-500">لا توجد سجلات تدقيق متاحة</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>