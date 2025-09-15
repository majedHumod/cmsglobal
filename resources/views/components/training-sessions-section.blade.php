<!-- قسم جلسات التدريب الخاصة -->
<section class="bg-white py-0" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            try {
                $trainingSessions = \App\Models\TrainingSession::getHomepageSessions();
            } catch (\Exception $e) {
                $trainingSessions = collect([]);
            }
        @endphp

        @if($trainingSessions->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start py-16">
                <!-- Left Column: Title, Description, and Contact Button -->
                <div class="lg:sticky lg:top-20">
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                                مدربونا الخبراء
                            </h2>
                            <p class="text-xl text-gray-600 leading-relaxed">
                                تعرف على مدربينا المعتمدين المتخصصين في إرشادك خلال رحلتك مع الدعم الشخصي والتعليمات الواعية وممارسات العافية الشاملة
                            </p>
                        </div>
                    @if($totalSessions > 4)
                        <div class="flex items-center justify-center h-80">
                            <div class="text-center">
                                <a href="{{ route('training-sessions.all') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-full hover:from-indigo-700 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <span>عرض جميع الجلسات</span>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Training Sessions Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($trainingSessions as $session)
                        <div class="training-session-card relative rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-80">
                            <!-- Background Image -->
                            @if($session->image)
                                <div class="absolute inset-0 w-full h-full">
                                    <img src="{{ Storage::url($session->image) }}" alt="{{ $session->title }}" class="w-full h-full object-cover" loading="lazy">
                                </div>
                            @else
                                <!-- Fallback gradient background -->
                                <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                            @endif
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            
                            <!-- Content Overlay -->
                            <div class="absolute inset-0 flex flex-col justify-end p-6 text-white">
                                <!-- Session Title -->
                                <h3 class="text-xl font-bold mb-2 text-right" style="direction: rtl;">
                                    {{ $session->title }}
                                </h3>
                                
                                <!-- Session Description -->
                                <p class="text-sm text-white/80 mb-4 leading-relaxed text-right" style="direction: rtl;">
                                    {{ Str::limit($session->description, 80) }}
                                </p>
                                
                                <!-- Session Details -->
                                <div class="flex items-center justify-between text-sm text-white/90 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ $session->duration_text }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-lg font-bold">{{ $session->formatted_price }}</span>
                                    </div>
                                </div>

                                <!-- Book Now Button -->
                                <a href="{{ route('training-sessions.show', $session) }}" class="block w-full text-center bg-white text-gray-900 font-semibold py-3 px-6 rounded-full hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                    احجز الآن
                                </a>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- View All Sessions Button -->