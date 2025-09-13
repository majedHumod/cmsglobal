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
                <!-- Left Column: Title, Description, and Contact Button -->
                <div class="lg:sticky lg:top-8">
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
                            @php
                                $contactWhatsapp = \App\Models\SiteSetting::get('contact_whatsapp');
                            @endphp
                            <a href="{{ $contactWhatsapp ? 'https://wa.me/' . str_replace(['+', ' ', '-'], '', $contactWhatsapp) : '#' }}" target="_blank" class="inline-flex items-center px-8 py-4 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-full hover:bg-gray-50 hover:border-gray-400 transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-lg">
                                <span>تواصل معنا</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Training Sessions Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($trainingSessions as $session)
                        <div class="training-session-card bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-full flex flex-col">
                            @if($session->image)
                                <div class="relative h-64 overflow-hidden">
                                    <img src="{{ Storage::url($session->image) }}" alt="{{ $session->title }}" class="w-full h-full object-cover" loading="lazy">
                                    
                                    <!-- Overlay with session info -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                    
                                    <!-- Session content overlay -->
                                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                        <h3 class="text-xl font-bold mb-2" style="direction: rtl; text-align: right;">
                                            {{ $session->title }}
                                        </h3>
                                        
                                        <p class="text-sm text-white/90 mb-3" style="direction: rtl; text-align: right;">
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
                            @else
                                <!-- Fallback for sessions without images -->
                                <div class="h-64 bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center relative">
                                    <div class="text-center text-white p-6">
                                        <svg class="h-16 w-16 mx-auto mb-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <h3 class="text-xl font-bold mb-2">{{ $session->title }}</h3>
                                        <p class="text-sm text-white/90 mb-4">{{ Str::limit($session->description, 80) }}</p>
                                        
                                        <!-- Session Details -->
                                        <div class="flex items-center justify-between text-sm text-white/90 mb-4">
                                            <span>{{ $session->duration_text }}</span>
                                            <span class="text-lg font-bold">{{ $session->formatted_price }}</span>
                                        </div>

                                        <!-- Book Now Button -->
                                        <a href="{{ route('training-sessions.show', $session) }}" class="inline-block bg-white text-gray-900 font-semibold py-3 px-6 rounded-full hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                            احجز الآن
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    
                    <!-- إذا كان هناك أكثر من 3 جلسات، أضف رابط لعرض المزيد -->
                    @php
                        try {
                            $totalSessions = \App\Models\TrainingSession::visible()->count();
                        } catch (\Exception $e) {
                            $totalSessions = $trainingSessions->count();
                        }
                    @endphp
                    
                    @if($totalSessions > 3)
                        <div class="md:col-span-2 text-center">
                            <a href="{{ route('training-sessions.all') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-full hover:from-indigo-700 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <span>عرض جميع الجلسات</span>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>

<style>
    .training-session-card {
        transition: all 0.3s ease;
        min-height: 320px;
    }
    
    .training-session-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
    }
</style>