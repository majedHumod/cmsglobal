<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Notes Card - Visible to both admin and user roles -->
                    @hasanyrole('admin|user')
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900">Notes Management</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Create and manage your personal notes.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('notes.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    View Notes
                                </a>
                            </div>
                        </div>
                    </div>
                    @endhasanyrole

                    <!-- Articles Card - Visible only to admin role -->
                    @role('admin')
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900">Articles Management</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Manage and publish articles for your website.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('articles.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Manage Articles
                                </a>
                            </div>
                        </div>
                    </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>