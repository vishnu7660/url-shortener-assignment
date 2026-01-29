<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Welcome Card -->
            <div class="bg-white shadow-md rounded-2xl p-6">
                <h3 class="text-xl font-semibold text-gray-800">
                    Welcome back, <span class="text-blue-600">{{ auth()->user()->name }}</span> 👋
                </h3>
                <p class="mt-2 text-gray-600">
                    Role: <span class="font-medium">{{ auth()->user()->role->name }}</span>
                </p>

                @if(auth()->user()->company)
                    <p class="text-gray-600">
                        Company: <span class="font-medium">{{ auth()->user()->company->name }}</span>
                    </p>
                @endif
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-gradient-to-r from-blue-500 to-blue-600   p-5 rounded-2xl shadow">
                    <p class="text-sm opacity-80">Total URLs</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_urls'] }}</p>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600   p-5 rounded-2xl shadow">
                    <p class="text-sm opacity-80">Total Clicks</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_clicks'] }}</p>
                </div>

                @if(auth()->user()->isSuperAdmin())
                <div class="bg-gradient-to-r from-purple-500 to-purple-600   p-5 rounded-2xl shadow">
                    <p class="text-sm opacity-80">Companies</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['companies_count'] }}</p>
                </div>
                @endif

                @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600   p-5 rounded-2xl shadow">
                    <p class="text-sm opacity-80">Users</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['users_count'] }}</p>
                </div>
                @endif

            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-md rounded-2xl p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    @if(auth()->user()->canCreateShortUrls())
                    <a href="{{ route('short-urls.create') }}"
                       class="w-full text-center bg-blue-600 hover:bg-blue-700 font-medium py-3 px-4 rounded-xl shadow transition duration-200">
                        ➕ Create Short URL
                    </a>
                    @endif

                    <a href="{{ route('short-urls.index') }}"
                       class="w-full text-center bg-gray-700 hover:bg-gray-800   font-medium py-3 px-4 rounded-xl shadow transition duration-200">
                        🔗 View URLs
                    </a>

                    @if(auth()->user()->canInviteUsers())
                    <a href="{{ route('invitations.create') }}"
                       class="w-full text-center bg-green-600 hover:bg-green-700   font-medium py-3 px-4 rounded-xl shadow transition duration-200">
                        👤 Invite User
                    </a>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
