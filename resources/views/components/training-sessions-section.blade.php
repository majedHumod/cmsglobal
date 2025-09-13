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
                    @foreach($trainingSessions as $index => $session)
                        @php
                            // تحديد الألوان والأنماط لكل بطاقة
                            $cardStyles = [
                                0 => [
                                    'bg' => 'bg-gradient-to-br from-green-700 to-green-800',
                                    'text' => 'text-white',
                                    'button' => 'bg-white text-green-700 hover:bg-gray-100'
                                ],
                                1 => [
                                    'bg' => 'bg-white border border-gray-200',
                                    'text' => 'text-gray-900',
                                    'button' => 'bg-green-600 text-white hover:bg-green-700'
                                ],
                                2 => [
                                    'bg' => 'bg-white border border-gray-200',
                                    'text' => 'text-gray-900',
                                    'button' => 'bg-green-600 text-white hover:bg-green-700'
                                ]
                            ];
                            
                            $style = $cardStyles[$index] ?? $cardStyles[1];
                        @endphp
                        
                        <div class="training-session-card {{ $style['bg'] }} rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-full flex flex-col">
                            @if($session->image)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ Storage::url($session->image) }}" alt="{{ $session->title }}" class="w-full h-full object-cover" loading="lazy">
                                </div>
                            @endif
                            
                            <div class="p-6 flex flex-col flex-grow">
                                <!-- Session Info -->
                                <div class="mb-6 flex-grow">
                                    <h3 class="text-xl font-bold {{ $style['text'] }} mb-3" style="direction: rtl; text-align: right;">
                                        {{ $session->title }}
                                    </h3>
                                    
                                    @if($index == 0)
                                        <!-- البطاقة الأولى - تصميم خاص -->
                                        <p class="text-white/90 leading-relaxed mb-4" style="direction: rtl; text-align: right;">
                                            {{ Str::limit($session->description, 120) }}
                                        </p>
                                    @else
                                        <!-- البطاقات الأخرى - تصميم عادي مع صورة المدرب -->
                                        <div class="flex items-start space-x-4 space-x-reverse mb-4">
                                            @if($session->image)
                                                <div class="flex-shrink-0">
                                                    <img src="{{ Storage::url($session->image) }}" alt="{{ $session->title }}" class="w-16 h-16 rounded-full object-cover border-4 border-gray-100" loading="lazy">
                                                </div>
                                            @else
                                                <div class="flex-shrink-0">
                                                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center border-4 border-gray-100">
                                                        <span class="text-white font-bold text-lg">{{ substr($session->title, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="flex-grow">
                                                <h4 class="font-semibold {{ $style['text'] }} mb-1">{{ $session->title }}</h4>
                                                <p class="text-sm text-gray-500 mb-2">مدرب معتمد</p>
                                                <p class="text-sm {{ $style['text'] }} leading-relaxed">
                                                    {{ Str::limit($session->description, 80) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Session Details -->
                                    <div class="flex items-center justify-between text-sm {{ $style['text'] }} mb-4">
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
                                </div>

                                <!-- Book Now Button -->
                                <div class="mt-auto">
                                    <a href="{{ route('training-sessions.show', $session) }}" class="block w-full text-center {{ $style['button'] }} font-semibold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                        @if($index == 0)
                                            حجز موعد
                                        @else
                                            احجز الآن
                                        @endif
                                    </a>
                                </div>
                            </div>
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
                        <div class="md:col-span-2 text-center mt-8">
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
        min-height: 400px;
    }
    
    .training-session-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
    }
    
    .training-session-card .flex-grow {
        display: flex;
        flex-direction: column;
    }
</style>