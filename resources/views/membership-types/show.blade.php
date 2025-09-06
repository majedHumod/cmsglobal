@extends('layouts.admin')

@section('title', 'تفاصيل نوع العضوية')

@section('header', 'تفاصيل نوع العضوية: ' . $membershipType->name)

@section('header_actions')
<div class="flex space-x-2">
    @if($membershipType->canBeModified())
        <a href="{{ route('membership-types.edit', $membershipType) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            تعديل
        </a>
    @endif
    <a href="{{ route('membership-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        العودة للقائمة
    </a>
</div>
@endsection

@section('content')
<!-- معلومات العضوية الأساسية -->
<div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mr-4">
                    @if($membershipType->is_protected)
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($membershipType->price == 0)
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $membershipType->name }}</h1>
                    {!! $membershipType->status_badge !!}
                </div>
            </div>
        </div>

        @if($membershipType->description)
            <p class="text-gray-600 text-lg mb-6">{{ $membershipType->description }}</p>
        @endif

        <!-- معلومات سريعة -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $membershipType->formatted_price }}</div>
                <div class="text-sm text-gray-600">السعر</div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-green-600">{{ $membershipType->duration_text }}</div>
                <div class="text-sm text-gray-600">المدة</div>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $membershipType->getActiveSubscribersCount() }}</div>
                <div class="text-sm text-gray-600">مشترك نشط</div>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $membershipType->sort_order }}</div>
                <div class="text-sm text-gray-600">ترتيب العرض</div>
            </div>
        </div>

        <!-- المميزات -->
        @if($membershipType->features && count($membershipType->features) > 0)
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">مميزات العضوية</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="space-y-3">
                        @foreach($membershipType->features as $feature)
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- معلومات تفصيلية -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- معلومات العضوية -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">معلومات العضوية</h3>
            <p class="mt-1 text-sm text-gray-500">تفاصيل تقنية عن نوع العضوية</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <dl class="space-y-4">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الرمز المميز:</dt>
                    <dd class="text-sm text-gray-900">{{ $membershipType->slug }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء:</dt>
                    <dd class="text-sm text-gray-900">{{ $membershipType->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">آخر تحديث:</dt>
                    <dd class="text-sm text-gray-900">{{ $membershipType->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">محمي من النظام:</dt>
                    <dd class="text-sm text-gray-900">{{ $membershipType->is_protected ? '🔒 نعم' : '✅ لا' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">يمكن تعديله:</dt>
                    <dd class="text-sm text-gray-900">{{ $membershipType->canBeModified() ? '✅ نعم' : '❌ لا' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">يمكن حذفه:</dt>
                    <dd class="text-sm text-gray-900">{{ $membershipType->canBeDeleted() ? '✅ نعم' : '❌ لا' }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- إحصائيات الاشتراكات -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">إحصائيات الاشتراكات</h3>
            <p class="mt-1 text-sm text-gray-500">بيانات المشتركين في هذا النوع من العضوية</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <dl class="space-y-4">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">إجمالي الاشتراكات:</dt>
                    <dd class="text-sm text-gray-900">{{ $membershipType->userMemberships->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الاشتراكات النشطة:</dt>
                    <dd class="text-sm text-gray-900">{{ $membershipType->activeUserMemberships->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الاشتراكات المنتهية:</dt>
                    <dd class="text-sm text-gray-900">{{ $membershipType->userMemberships->count() - $membershipType->activeUserMemberships->count() }}</dd>
                </div>
                @if($membershipType->price > 0)
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">إجمالي الإيرادات:</dt>
                        <dd class="text-sm text-gray-900">
                            @php
                                $totalRevenue = $membershipType->userMemberships->where('payment_status', 'paid')->sum('payment_amount');
                            @endphp
                            {{ number_format($totalRevenue, 2) }} ريال
                        </dd>
                    </div>
                @endif
            </dl>
            
            <!-- رسم بياني بسيط -->
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">توزيع الاشتراكات</h4>
                @php
                    $activeCount = $membershipType->activeUserMemberships->count();
                    $expiredCount = $membershipType->userMemberships->count() - $activeCount;
                    $total = $membershipType->userMemberships->count();
                @endphp
                
                @if($total > 0)
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">نشطة</span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($activeCount / $total) * 100 }}%"></div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $activeCount }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">منتهية</span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $total > 0 ? ($expiredCount / $total) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $expiredCount }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">لا توجد اشتراكات بعد</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- قائمة المشتركين -->
@if($membershipType->userMemberships->count() > 0)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">المشتركين في هذه العضوية</h3>
                    <p class="mt-1 text-sm text-gray-500">قائمة بجميع المستخدمين المشتركين في هذا النوع من العضوية</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                    {{ $membershipType->userMemberships->count() }} اشتراك
                </span>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ البداية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الانتهاء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الدفع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($membershipType->userMemberships->take(10) as $membership)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ $membership->user->profile_photo_url }}" alt="{{ $membership->user->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $membership->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $membership->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $membership->starts_at ? $membership->starts_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $membership->expires_at ? $membership->expires_at->format('d/m/Y') : '-' }}
                                    @if($membership->expires_at && $membership->expires_at > now())
                                        <div class="text-xs text-gray-500">
                                            ({{ $membership->days_remaining }} يوم متبقي)
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $membership->status_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $membership->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($membership->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($membership->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($membership->payment_amount, 2) }} ريال
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($membershipType->userMemberships->count() > 10)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <p class="text-sm text-gray-500 text-center">عرض 10 من أصل {{ $membershipType->userMemberships->count() }} اشتراك</p>
                    <div class="mt-2 text-center">
                        <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                            عرض جميع الاشتراكات
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    <!-- لا توجد اشتراكات -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="text-center py-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد اشتراكات</h3>
                <p class="text-sm text-gray-500 mb-6">لم يشترك أي مستخدم في هذا النوع من العضوية بعد.</p>
                
                @if($membershipType->is_active)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">نصائح لزيادة الاشتراكات</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>تأكد من وضوح المميزات المقدمة</li>
                                        <li>راجع التسعير مقارنة بالمنافسين</li>
                                        <li>أضف محتوى حصري لهذا النوع من العضوية</li>
                                        <li>قم بالترويج للعضوية في الصفحة الرئيسية</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">العضوية غير نشطة</h3>
                                <p class="mt-1 text-sm text-yellow-700">هذا النوع من العضوية غير نشط حالياً، لذلك لا يمكن للمستخدمين الاشتراك فيه.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
@endsection