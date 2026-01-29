<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invitations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header with Send Invitation Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Pending Invitations</h3>
                        @if (auth()->user()->canInviteUsers())
                            <a href="{{ route('invitations.create') }}"
                                style="display: inline-block; background-color: #eb7f25; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600; text-decoration: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                ➕ Send Invitation
                            </a>
                        @endif
                    </div>

                    @if ($invitations->isEmpty())
                        <p class="text-gray-500">No pending invitations.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role
                                        </th>
                                        @if (auth()->user()->isSuperAdmin())
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Company</th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Invited By</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Invitation Link</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sent
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($invitations as $invitation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $invitation->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $invitation->role->name }}
                                            </td>
                                            @if (auth()->user()->isSuperAdmin())
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ $invitation->company->name }}
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $invitation->inviter->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('invitations.accept', $invitation->token) }}"
                                                    class="text-blue-600 hover:underline" target="_blank">
                                                    View Link
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $invitation->created_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $invitations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
