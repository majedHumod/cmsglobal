@extends('layouts.app')

@section('title', 'الأسئلة الشائعة')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">الأسئلة الشائعة</h1>
                
                @if($faqs->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">لا توجد أسئلة شائعة</h3>
                        <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي أسئلة شائعة بعد.</p>
                    </div>
                @else
                    <div class="space-y-8" x-data="{ activeCategory: null }">
                        @foreach($faqs as $category => $categoryFaqs)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <button 
                                        @click="activeCategory = activeCategory === '{{ $category }}' ? null : '{{ $category }}'"
                                        class="flex justify-between items-center w-full text-right focus:outline-none"
                                    >
                                        <span>{{ $category }}</span>
                                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-300" :class="{'rotate-180': activeCategory === '{{ $category }}'}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </h2>
                                
                                <div x-show="activeCategory === '{{ $category }}'" x-collapse>
                                    <div class="space-y-4 mt-4">
                                        @foreach($categoryFaqs as $faq)
                                            <div class="bg-white rounded-lg shadow-sm p-4" x-data="{ open: false }">
                                                <button 
                                                    @click="open = !open" 
                                                    class="flex justify-between items-center w-full text-right focus:outline-none"
                                                >
                                                    <h3 class="text-lg font-medium text-gray-900">{{ $faq->question }}</h3>
                                                    <svg class="w-5 h-5 text-gray-500 transition-transform duration-300" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                                
                                                <div x-show="open" x-collapse class="mt-4 text-gray-600 prose max-w-none">
                                                    {!! $faq->answer !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-12 text-center">
                        <p class="text-gray-600 mb-4">لم تجد إجابة لسؤالك؟</p>
                        <a href="#" class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            تواصل معنا
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection