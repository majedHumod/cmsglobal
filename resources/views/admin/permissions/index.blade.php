<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🔐 {{ __('إدارة الصلاحيات المتقدمة') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.permissions.report') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    📊 التقارير
                </a>
                <a href="{{ route('admin.permissions.groups') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    📁 إدارة المجموعات
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- إحصائيات سريعة -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">🔑</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">إجمالي الصلاحيات</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $statistics['total_permissions'] ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">👥</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">الأدوار النشطة</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $statistics['active_roles'] ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">⚡</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">التجاوزات النشطة</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $statistics['active_overrides'] ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">📝</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">التغييرات الأخيرة</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $statistics['recent_changes'] ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- مجموعات الصلاحيات -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md mb-8">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">مجموعات الصلاحيات</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">عرض الصلاحيات مجمعة حسب الفئات والمجموعات</p>
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
                                        {{ $group->permissions_count }} صلاحية
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $group->categories_count }} تصنيف</span>
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
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">سجل التغييرات الأخيرة</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">آخر التغييرات على الصلاحيات والأدوار</p>
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
        </div>
    </div>
</x-app-layout>