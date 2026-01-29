<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Short URL') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('short-urls.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="original_url" class="block text-sm font-medium text-gray-700">Original URL</label>
                            <input
                                type="url"
                                name="original_url"
                                id="original_url"
                                value="{{ old('original_url') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="https://example.com/very/long/url"
                                required
                            >
                            @error('original_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" style="background-color: #16a34a; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                                Create Short URL
                            </button>&nbsp;&nbsp;&nbsp;
                            <a href="{{ route('short-urls.index') }}" class="bg-gray-500    px-4 py-2 rounded hover:bg-gray-600">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
