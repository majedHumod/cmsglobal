<!-- قسم قصص النجاح -->
<section class="bg-gray-50 py-16" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">قصص نجاح عملائنا</h2>
            <p class="mt-4 text-xl text-gray-600">اكتشف كيف غيرت خدماتنا حياة عملائنا</p>
        </div>

        @php
            try {
                $testimonials = \App\Models\Testimonial::getVisibleTestimonials();
            } catch (\Exception $e) {
                $testimonials = collect([]);
            }
        @endphp

        @if($testimonials->count() > 0)
            @if($testimonials->count() == 1)
                <!-- عرض قصة واحدة -->
                <div class="max-w-4xl mx-auto">
                    @php $testimonial = $testimonials->first(); @endphp
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <!-- Quote Icon -->
                        <div class="text-center mb-6">
                            <svg class="w-16 h-16 text-indigo-200 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                            </svg>
                        </div>

                        <!-- Story Content -->
                        <div class="text-center mb-8">
                            <p class="text-gray-700 text-xl leading-relaxed" style="direction: rtl; text-align: center;">
                                "{{ $testimonial->story_content }}"
                            </p>
                        </div>

                        <!-- Author Info -->
                        <div class="text-center">
                            @if($testimonial->image)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($testimonial->image) }}" alt="{{ $testimonial->name }}" class="w-20 h-20 rounded-full object-cover mx-auto" loading="lazy">
                                </div>
                            @endif
                            <h4 class="text-xl font-semibold text-gray-900">{{ $testimonial->name }}</h4>
                        </div>
                    </div>
                </div>

            @elseif($testimonials->count() == 2)
                <!-- عرض قصتين جنباً إلى جنب -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($testimonials as $testimonial)
                        <div class="bg-white rounded-xl shadow-lg p-8 h-full flex flex-col">
                            <!-- Quote Icon -->
                            <div class="mb-6">
                                <svg class="w-12 h-12 text-indigo-200" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                </svg>
                            </div>

                            <!-- Story Content -->
                            <div class="flex-grow mb-6">
                                <p class="text-gray-700 text-lg leading-relaxed" style="direction: rtl; text-align: right;">
                                    "{{ $testimonial->story_content }}"
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
                                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900" style="direction: rtl; text-align: right;">{{ $testimonial->name }}</h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <!-- عرض أكثر من قصتين مع Carousel -->
                <div class="relative">
                    <div class="testimonials-carousel overflow-hidden" x-data="{
                        currentSlide: 0,
                        totalSlides: {{ $testimonials->count() }},
                        slidesToShow: window.innerWidth >= 768 ? 2 : 1,
                        
                        nextSlide() {
                            if (this.currentSlide < this.totalSlides - this.slidesToShow) {
                                this.currentSlide++;
                            } else {
                                this.currentSlide = 0;
                            }
                        },
                        
                        prevSlide() {
                            if (this.currentSlide > 0) {
                                this.currentSlide--;
                            } else {
                                this.currentSlide = Math.max(0, this.totalSlides - this.slidesToShow);
                            }
                        },
                        
                        goToSlide(index) {
                            this.currentSlide = index;
                        }
                    }" x-init="
                        setInterval(() => {
                            nextSlide();
                        }, 5000);
                        
                        window.addEventListener('resize', () => {
                            slidesToShow = window.innerWidth >= 768 ? 2 : 1;
                        });
                    ">
                        <div class="flex transition-transform duration-500 ease-in-out"
                             :style="`transform: translateX(-${currentSlide * (100 / slidesToShow)}%); width: ${totalSlides * (100 / slidesToShow)}%`">
                            @foreach($testimonials as $testimonial)
                                <div class="flex-shrink-0 px-3" :style="`width: ${100 / slidesToShow}%`">
                                    <div class="bg-white rounded-xl shadow-lg p-8 h-full flex flex-col">
                                        <!-- Quote Icon -->
                                        <div class="mb-6">
                                            <svg class="w-12 h-12 text-indigo-200" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                            </svg>
                                        </div>

                                        <!-- Story Content -->
                                        <div class="flex-grow mb-6">
                                            <p class="text-gray-700 text-lg leading-relaxed" style="direction: rtl; text-align: right;">
                                                "{{ $testimonial->story_content }}"
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
                                                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900" style="direction: rtl; text-align: right;">{{ $testimonial->name }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Navigation Arrows -->
                        <button @click="prevSlide()" aria-label="Slide previous" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-lg hover:shadow-xl transition-all duration-200 z-10">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>

                        <button @click="nextSlide()" aria-label="Slide next" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-lg hover:shadow-xl transition-all duration-200 z-10">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        <!-- Dots Indicator -->
                        <div class="flex justify-center mt-8 space-x-2">
                            <template x-for="(dot, index) in Array.from({length: Math.max(1, totalSlides - slidesToShow + 1)}, (_, i) => i)" :key="index">
                                <button @click="goToSlide(index)" 
                                        class="w-3 h-3 rounded-full transition-colors duration-200"
                                        :class="currentSlide === index ? 'bg-indigo-600' : 'bg-gray-300'">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- رسالة عدم وجود قصص -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد قصص نجاح</h3>
                <p class="text-sm text-gray-500">لم يتم إضافة أي قصص نجاح بعد.</p>
            </div>
        @endif
    </div>
</section>
