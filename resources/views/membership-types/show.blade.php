<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تفاصيل العضوية: ') . $membershipType->name }}
            </h2>
            <div class="flex space-x-2">
                @if($membershipType->canBeModified())
                    <a href="{{ route('membership-types.edit', $membershipType) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        تعديل
                    </a>
                @endif
                <a href="{{ route('membership-types.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    العودة للقائمة
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- معلومات العضوية الأساسية -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $membershipType->name }}</h1>
                        {!! $membershipType->status_badge !!}
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

                    <!-- معلومات إضافية -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">معلومات العضوية</h3>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">الرمز المميز:</dt>
                                        <dd class="text-sm text-gray-900">{{ $membershipType->slug }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">تاريخ الإنشاء:</dt>
                                        <dd class="text-sm text-gray-900">{{ $membershipType->created_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">آخر تحديث:</dt>
                                        <dd class="text-sm text-gray-900">{{ $membershipType->updated_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">محمي من النظام:</dt>
                                        <dd class="text-sm text-gray-900">{{ $membershipType->is_protected ? '🔒 نعم' : '✅ لا' }}</dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">إحصائيات الاشتراكات</h3>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">إجمالي الاشتراكات:</dt>
                                        <dd class="text-sm text-gray-900">{{ $membershipType->userMemberships->count() }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">الاشتراكات النشطة:</dt>
                                        <dd class="text-sm text-gray-900">{{ $membershipType->activeUserMemberships->count() }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">الاشتراكات المنتهية:</dt>
                                        <dd class="text-sm text-gray-900">{{ $membershipType->userMemberships->count() - $membershipType->activeUserMemberships->count() }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قائمة المشتركين -->
            @if($membershipType->userMemberships->count() > 0)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">المشتركين في هذه العضوية</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ البداية</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الانتهاء</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الدفع</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($membershipType->userMemberships->take(10) as $membership)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
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
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">عرض 10 من أصل {{ $membershipType->userMemberships->count() }} اشتراك</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>