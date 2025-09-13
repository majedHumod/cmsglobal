<!-- قسم جلسات التدريب الخاصة -->
<section class="bg-white py-16" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            try {
                $trainingSessions = \App\Models\TrainingSession::getHomepageSessions();
            } catch (\Exception $e) {
                $trainingSessions = collect([]);
            }
        @endphp

        @if($trainingSessions->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <!-- Left Column: Title, Description, and View More Button -->
                <div class="lg:sticky lg:top-8">
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                                جلسات التدريب الخاصة
                            </h2>
                            <p class="text-xl text-gray-600 leading-relaxed">
                                احجز جلسة تدريب خاصة مع مدربينا المحترفين واحصل على برنامج تدريبي مخصص يناسب أهدافك وقدراتك الشخصية.
                            </p>
                        </div>
                        
                        <div>
                            <a href="{{ route('training-sessions.all') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <span>عرض جميع الجلسات</span>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Training Sessions Cards -->
                <div class="space-y-6">
                    @foreach($trainingSessions as $session)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            @if($session->image)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ Storage::url($session->image) }}" alt="{{ $session->title }}" class="w-full h-full object-cover" loading="lazy">
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <!-- Session Info -->
                                <div class="mb-4">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2" style="direction: rtl; text-align: right;">
                                        {{ $session->title }}
                                    </h3>
                                    <p class="text-gray-600 leading-relaxed" style="direction: rtl; text-align: right;">
                                        {{ Str::limit($session->description, 120) }}
                                    </p>
                                </div>

                                <!-- Session Details -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4 space-x-reverse">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-indigo-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ $session->duration_text }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <span class="text-lg font-bold text-green-600">{{ $session->formatted_price }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Book Now Button -->
                                <div class="mt-6">
                                    <a href="{{ route('training-sessions.show', $session) }}" class="block w-full text-center bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                        احجز الآن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>