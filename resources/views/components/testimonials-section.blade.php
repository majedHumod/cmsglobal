<!-- قسم قصص النجاح -->
<section class="bg-gray-50 py-0" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            try {
                $testimonials = \App\Models\Testimonial::getVisibleTestimonials();
                // عرض 3 قصص فقط في الصفحة الرئيسية
                $testimonials = $testimonials->take(3);
            } catch (\Exception $e) {
                $testimonials = collect([]);
            }
        @endphp

        @if($testimonials->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <!-- Left Column: Title, Description, and View More Button -->
                <div class="lg:sticky lg:top-8">
                    <div class="space-y-6">
                        <div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start py-16">
                <!-- Left Column: Title, Description, and View More Button -->
                <div class="lg:sticky lg:top-20">
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                                ماذا يقول عملاؤنا
                            </h2>
                            <p class="text-xl text-gray-600 leading-relaxed">
                                اكتشف تجارب عملائنا الحقيقية وكيف ساعدتهم خدماتنا في تحقيق أهدافهم وتحسين حياتهم بطرق مذهلة ومؤثرة.
                            </p>
                        </div>
                        
                        <div>
                            <a href="{{ route('testimonials.all') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <span>عرض المزيد</span>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Testimonials Cards -->
                <div class="space-y-6">
                    <!-- عرض 3 قصص فقط بتصميم عمودي -->
                    @foreach($testimonials as $testimonial)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Quote Icon -->
                            <div class="flex justify-end mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                </svg>
                            </div>

                            <!-- Story Content -->
                            <div class="mb-6">
                                <p class="text-gray-700 text-lg leading-relaxed" style="direction: rtl; text-align: right;">
                                    {{ $testimonial->story_content }}
                                </p>
                            </div>

                            <!-- Author Info -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($testimonial->image)
                                        <div class="flex-shrink-0 mr-4">
                                            <img src="{{ Storage::url($testimonial->image) }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full object-cover" loading="lazy">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-lg">{{ substr($testimonial->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900" style="direction: rtl; text-align: right;">{{ $testimonial->name }}</h4>
                                        <p class="text-sm text-gray-500">عميل راضٍ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>