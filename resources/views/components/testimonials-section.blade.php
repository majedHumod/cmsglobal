<!-- قسم قصص النجاح -->
<section class="bg-white py-16" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            try {
                $testimonials = \App\Models\Testimonial::getVisibleTestimonials();
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
                    @if($testimonials->count() <= 3)
                        <!-- عرض مباشر للقصص القليلة -->
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
                    @else
                        <!-- Carousel للقصص الكثيرة -->
                        <div class="relative" x-data="{
                            currentSlide: 0,
                            totalSlides: {{ $testimonials->count() }},
                            slidesToShow: 2,
                            maxSlides: Math.max(0, {{ $testimonials->count() }} - 2),
                            
                            nextSlide() {
                                if (this.currentSlide < this.maxSlides) {
                                    this.currentSlide++;
                                } else {
                                    this.currentSlide = 0;
                                }
                            },
                            
                            prevSlide() {
                                if (this.currentSlide > 0) {
                                    this.currentSlide--;
                                } else {
                                    this.currentSlide = this.maxSlides;
                                }
                            },
                            
                            goToSlide(index) {
                                this.currentSlide = Math.min(index, this.maxSlides);
                            }
                        }" x-init="
                            setInterval(() => {
                                nextSlide();
                            }, 6000);
                        ">
                            <!-- Carousel Container -->
                            <div class="overflow-hidden rounded-2xl">
                                <div class="flex transition-transform duration-500 ease-in-out space-y-6 flex-col"
                                     :style="`transform: translateY(-${currentSlide * 100}%)`">
                                    @foreach($testimonials as $index => $testimonial)
                                        <div class="flex-shrink-0 w-full">
                                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
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

                            <!-- Navigation Arrows -->
                            @if($testimonials->count() > 2)
                                <button @click="prevSlide()" aria-label="القصة السابقة" class="absolute top-4 right-4 bg-white rounded-full p-2 shadow-lg hover:shadow-xl transition-all duration-200 z-10">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>

                                <button @click="nextSlide()" aria-label="القصة التالية" class="absolute bottom-4 right-4 bg-white rounded-full p-2 shadow-lg hover:shadow-xl transition-all duration-200 z-10">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dots Indicator -->
                                <div class="flex justify-center mt-6 space-x-2">
                                    <template x-for="(dot, index) in Array.from({length: maxSlides + 1}, (_, i) => i)" :key="index">
                                        <button @click="goToSlide(index)" 
                                                class="w-3 h-3 rounded-full transition-colors duration-200"
                                                :class="currentSlide === index ? 'bg-indigo-600' : 'bg-gray-300'">
                                        </button>
                                    </template>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>