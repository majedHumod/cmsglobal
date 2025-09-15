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
                        
                        <div>
                            <a href="{{ route('training-sessions.all') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <span>تصفح جميع الجلسات</span>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
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
                    
                    <!-- View All Sessions Button (if more than 4 sessions exist) -->
                    @php
                        try {
                            $totalSessions = \App\Models\TrainingSession::visible()->count();
                        } catch (\Exception $e) {
                            $totalSessions = 0;
                        }
                    @endphp
                    
                    @if($totalSessions > 4)
                        <div class="training-session-card relative rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-80 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <div class="text-center p-6">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">المزيد من الجلسات</h3>
                                <p class="text-gray-600 mb-4">اكتشف جميع جلسات التدريب المتاحة</p>
                                <a href="{{ route('training-sessions.all') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105">
                                    <span>عرض الكل</span>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>

<style>
    /* Training sessions specific styles */
    .training-session-card {
        transition: all 0.3s ease;
    }
    
    .training-session-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* Ensure proper spacing and no overlap */
    .training-session-card .absolute {
        position: absolute;
    }
    
    .training-session-card .relative {
        position: relative;
    }
    
    /* RTL support for training sessions */
    [dir="rtl"] .training-session-card {
        text-align: right;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .training-session-card {
            height: 300px;
        }
        
        .training-session-card h3 {
            font-size: 1.125rem;
        }
        
        .training-session-card p {
            font-size: 0.875rem;
        }
    }
    
    /* Hover effects */
    .training-session-card:hover .bg-white {
        background-color: #f3f4f6;
    }
    
    .training-session-card:hover .transform {
        transform: scale(1.02);
    }
</style>