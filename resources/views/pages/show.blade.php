<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $page->seo_title }} - {{ config('app.name', 'Laravel') }}</title>
    
    @if($page->seo_description)
        <meta name="description" content="{{ $page->seo_description }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Styles for rich content -->
    <style>
        .prose {
            max-width: none;
        }
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .prose table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        .prose table th,
        .prose table td {
            border: 1px solid #e5e7eb;
            padding: 0.5rem;
            text-align: right;
        }
        .prose table th {
            background-color: #f9fafb;
            font-weight: 600;
        }
        .prose blockquote {
            border-right: 4px solid #6366f1;
            padding-right: 1rem;
            margin: 1rem 0;
            font-style: italic;
            background-color: #f8fafc;
            padding: 1rem;
            border-radius: 4px;
        }
        .prose ul, .prose ol {
            padding-right: 1.5rem;
        }
        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .prose h1 { font-size: 2.25rem; }
        .prose h2 { font-size: 1.875rem; }
        .prose h3 { font-size: 1.5rem; }
        .prose h4 { font-size: 1.25rem; }
        .prose h5 { font-size: 1.125rem; }
        .prose h6 { font-size: 1rem; }
        .prose p {
            margin-bottom: 1rem;
            line-height: 1.75;
        }
        .prose a {
            color: #6366f1;
            text-decoration: underline;
        }
        .prose a:hover {
            color: #4f46e5;
        }
        .prose code {
            background-color: #f1f5f9;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
        .prose pre {
            background-color: #1e293b;
            color: #f1f5f9;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1rem 0;
        }
        .prose pre code {
            background-color: transparent;
            padding: 0;
            color: inherit;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-xl font-bold text-gray-800">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">لوحة التحكم</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">تسجيل الدخول</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <article class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    @if($page->featured_image)
                        <div class="w-full h-64 md:h-80">
                            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-6 md:p-8">
                        <header class="mb-8">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                            
                            @if($page->excerpt)
                                <p class="text-xl text-gray-600 leading-relaxed">{{ $page->excerpt }}</p>
                            @endif

                            <div class="flex items-center text-sm text-gray-500 mt-4">
                                <span>نُشر بواسطة {{ $page->user->name }}</span>
                                @if($page->published_at)
                                    <span class="mx-2">•</span>
                                    <time datetime="{{ $page->published_at->toISOString() }}">
                                        {{ $page->published_at->format('d F Y') }}
                                    </time>
                                @endif
                            </div>
                        </header>

                        <div class="prose prose-lg max-w-none">
                            {!! $page->content !!}
                        </div>

                        @if($page->updated_at != $page->created_at)
                            <footer class="mt-8 pt-6 border-t border-gray-200">
                                <p class="text-sm text-gray-500">
                                    آخر تحديث: {{ $page->updated_at->format('d F Y H:i') }}
                                </p>
                            </footer>
                        @endif
                    </div>
                </article>

                <!-- Navigation Links -->
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('pages.public') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        عرض جميع الصفحات
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>