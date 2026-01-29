<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Short URLs') }}
            </h2>
            @if(auth()->user()->canCreateShortUrls())
                <a href="{{ route('short-urls.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Create New
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($shortUrls->isEmpty())
                        <p class="text-gray-500">No short URLs found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Short Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Original URL</th>
                                        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created By</th>
                                        @endif
                                        @if(auth()->user()->isSuperAdmin())
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clicks</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($shortUrls as $shortUrl)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ url($shortUrl->short_code) }}" target="_blank" class="text-blue-600 hover:underline">
                                                    {{ $shortUrl->short_code }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 truncate max-w-md" title="{{ $shortUrl->original_url }}">
                                                    {{ Str::limit($shortUrl->original_url, 50) }}
                                                </div>
                                            </td>
                                            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ $shortUrl->user->name }}
                                                </td>
                                            @endif
                                            @if(auth()->user()->isSuperAdmin())
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ $shortUrl->company->name }}
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $shortUrl->clicks }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $shortUrl->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <form action="{{ route('short-urls.destroy', $shortUrl) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $shortUrls->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
