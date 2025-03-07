<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notes') }}
            </h2>
            <a href="{{ route('notes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Note
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
                    @if($notes->isEmpty())
                        <p class="text-gray-500 text-center">No notes found.</p>
                    @else
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($notes as $note)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $note->title }}</h3>
                                            <p class="text-gray-600 mt-2">{{ $note->content }}</p>
                                            <p class="text-sm text-gray-500 mt-2">Created by: {{ $note->user->name }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('notes.edit', $note) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                            <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this note?')">Delete</button>
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