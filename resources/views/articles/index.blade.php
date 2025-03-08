<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Articles Management') }}
            </h2>
            <a href="{{ route('articles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Article
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if($articles->isEmpty())
                        <p class="text-gray-500 text-center">No articles found.</p>
                    @else
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($articles as $article)
                                <div class="border rounded-lg p-6">
                                    <div class="flex justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold">{{ $article->title }}</h3>
                                            @if($article->image)
                                                <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="mt-2 w-48 h-48 object-cover rounded">
                                            @endif
                                            <div class="mt-4 text-gray-600">
                                                {{ Str::limit($article->content, 200) }}
                                            </div>
                                            <p class="text-sm text-gray-500 mt-2">
                                                Created by: {{ $article->user->name }} | {{ $article->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-2 ml-4">
                                            <a href="{{ route('articles.edit', $article) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this article?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>