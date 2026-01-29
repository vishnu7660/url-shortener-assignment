<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-gray-100">

        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Accept Invitation</h2>
            <p class="text-sm text-gray-500 mt-1">
                Join <span class="font-semibold text-indigo-600">{{ $invitation->company->name }}</span> as
                <span class="font-semibold">{{ $invitation->role->name }}</span>
            </p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('invitations.accept.store', $invitation->token) }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" value="{{ $invitation->email }}"
                    class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed" disabled>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                    Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
            </div>


            <button type="submit"
                style="width: 100%; background-color: #16a34a; color: white; padding: 10px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                Create Account & Accept Invitation
            </button>
        </form>

        <p class="text-xs text-center text-gray-400 mt-6">
            This invitation link will expire once your account is created.
        </p>
    </div>

</body>

</html>
