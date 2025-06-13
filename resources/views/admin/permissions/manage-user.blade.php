@extends('layouts.admin')

@section('title', 'إدارة صلاحيات المستخدم')

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
        إدارة صلاحيات المستخدم
    </span>
</li>
@endsection

@section('header', 'إدارة صلاحيات المستخدم: ' . $user->name)

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        العودة
    </a>
</div>
@endsection

@section('content')
<!-- معلومات المستخدم -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">معلومات المستخدم</h3>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-gray-200">
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">الاسم</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->name }}</dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">البريد الإلكتروني</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">الأدوار الحالية</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    @forelse($user->roles as $role)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                            {{ $role->name }}
                        </span>
                    @empty
                        <span class="text-gray-500">لا توجد أدوار</span>
                    @endforelse
                </dd>
            </div>
        </dl>
    </div>
</div>

<!-- منح تجاوز صلاحية -->
<div class="bg-white shadow sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">منح تجاوز صلاحية</h3>
        
        <form action="{{ route('admin.permissions.grant-override', $user) }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="permission" class="block text-sm font-medium text-gray-700">الصلاحية</label>
                    <select name="permission" id="permission" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">اختر الصلاحية</option>
                        @foreach($permissionGroups as $group)
                            <optgroup label="{{ $group->name }}">
                                @foreach($group->categories as $category)
                                    @foreach($category->permissions as $permission)
                                        <option value="{{ $permission->name }}">{{ $permission->name }} ({{ $permission->level }})</option>
                                    @endforeach
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">نوع التجاوز</label>
                    <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="grant">منح الصلاحية</option>
                        <option value="deny">منع الصلاحية</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700">سبب التجاوز</label>
                <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="اكتب سبب منح هذا التجاوز..." required></textarea>
            </div>
            
            <div>
                <label for="expires_at" class="block text-sm font-medium text-gray-700">تاريخ الانتهاء (اختياري)</label>
                <input type="datetime-local" name="expires_at" id="expires_at" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-sm text-gray-500">اتركه فارغاً للتجاوز الدائم</p>
            </div>
            
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    منح التجاوز
                </button>
            </div>
        </form>
    </div>
</div>

<!-- التجاوزات الحالية -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">التجاوزات الحالية</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">قائمة بجميع تجاوزات الصلاحيات للمستخدم</p>
        </div>
    </div>
    <div class="border-t border-gray-200">
        @forelse($overrides as $override)
            <div class="px-4 py-4 border-b border-gray-200 last:border-b-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            {!! $override->status_badge !!}
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $override->permission->name }}</p>
                            <p class="text-sm text-gray-500">
                                منح بواسطة: {{ $override->grantedBy->name ?? 'غير محدد' }}
                                @if($override->expires_at)
                                    | ينتهي: {{ $override->expires_at->format('Y-m-d H:i') }}
                                @else
                                    | دائم
                                @endif
                            </p>
                            @if($override->reason)
                                <p class="text-sm text-gray-600 mt-1">
                                    <strong>السبب:</strong> {{ $override->reason }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @if($override->is_valid)
                            <form action="{{ route('admin.permissions.revoke-override', [$user, $override]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="reason" value="سحب يدوي من لوحة التحكم">
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('هل أنت متأكد من سحب هذا التجاوز؟')">
                                    سحب
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="px-4 py-8 text-center">
                <p class="text-gray-500">لا توجد تجاوزات حالية</p>
            </div>
        @endforelse
    </div>
</div>

<!-- سجل التدقيق للمستخدم -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">سجل التدقيق</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">سجل بجميع التغييرات على صلاحيات هذا المستخدم</p>
        </div>
    </div>
    <div class="border-t border-gray-200">
        @forelse($auditLogs as $log)
            <div class="px-4 py-4 border-b border-gray-200 last:border-b-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $log->action === 'granted' ? 'bg-green-100 text-green-800' : 
                               ($log->action === 'revoked' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $log->action_text }}
                        </span>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $log->permission_name }}</p>
                            <p class="text-sm text-gray-500">
                                بواسطة: {{ $log->user->name ?? 'النظام' }}
                                @if($log->ip_address)
                                    من {{ $log->ip_address }}
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
                <p class="text-gray-500">لا توجد سجلات تدقيق</p>
            </div>
        @endforelse
    </div>
</div>
@endsection