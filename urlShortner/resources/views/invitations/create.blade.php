<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Send Invitation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('invitations.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role_id" id="role_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Select a role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if (auth()->user()->isSuperAdmin())
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Company</label>
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="company_action" value="existing" class="form-radio"
                                            {{ old('company_action') == 'existing' ? 'checked' : '' }}
                                            onchange="toggleCompanyFields()">
                                        <span class="ml-2">Existing Company</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" name="company_action" value="new" class="form-radio"
                                            {{ old('company_action') == 'new' ? 'checked' : '' }}
                                            onchange="toggleCompanyFields()">
                                        <span class="ml-2">New Company</span>
                                    </label>
                                </div>
                                @error('company_action')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="existing-company" class="mb-4" style="display: none;">
                                <label for="company_id" class="block text-sm font-medium text-gray-700">Select
                                    Company</label>
                                <select name="company_id" id="company_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select a company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="new-company" class="mb-4" style="display: none;">
                                <label for="company_name" class="block text-sm font-medium text-gray-700">Company
                                    Name</label>
                                <input type="text" name="company_name" id="company_name"
                                    value="{{ old('company_name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <script>
                                function toggleCompanyFields() {
                                    const action = document.querySelector('input[name="company_action"]:checked')?.value;
                                    const existingDiv = document.getElementById('existing-company');
                                    const newDiv = document.getElementById('new-company');

                                    if (action === 'existing') {
                                        existingDiv.style.display = 'block';
                                        newDiv.style.display = 'none';
                                    } else if (action === 'new') {
                                        existingDiv.style.display = 'none';
                                        newDiv.style.display = 'block';
                                    }
                                }

                                // Call on page load if value exists
                                document.addEventListener('DOMContentLoaded', toggleCompanyFields);
                            </script>
                        @endif

                        <div class="flex gap-3">

                            <button type="submit"
                                style="background-color: #16a34a; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                                Send Invitation
                            </button>&nbsp;&nbsp;&nbsp;
                            <a href="{{ route('invitations.index') }}"
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@if ($errors->any())
    <div style="background:#dc2626;color:white;padding:12px;border-radius:6px;margin-bottom:15px;">
        <strong>Validation Errors:</strong>
        <ul style="margin:5px 0 0 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

