<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invite User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {!! session('success') !!}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('invitations.store') }}">
                    @csrf

                    @if (auth()->user()->isSuperAdmin())
                        <div class="mb-4">
                            <label for="company_name" class="block font-medium text-sm text-gray-700">
                                Company Name
                            </label>

                            <input
                                id="company_name"
                                name="company_name"
                                type="text"
                                value="{{ old('company_name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300"
                            >
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="email" class="block font-medium text-sm text-gray-700">
                            Email
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            class="mt-1 block w-full rounded-md border-gray-300"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="role" class="block font-medium text-sm text-gray-700">
                            Role
                        </label>

                        <select
                            id="role"
                            name="role"
                            class="mt-1 block w-full rounded-md border-gray-300"
                            required
                        >
                            <option value="admin">Admin</option>

                            @if (auth()->user()->isAdmin())
                                <option value="member">Member</option>
                            @endif
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                    >
                        Send Invitation
                    </button>

                </form>


                @if (config('mail.default') === 'log')
                    <br>
                    <div class="mb-5 rounded-md bg-yellow-50 p-4">
                        <p class="text-sm font-medium text-yellow-800">
                            Note: <span class="text-red-600">Email service is not configured.</span>
                        </p>
                        <p class="mt-1 text-sm text-yellow-700">
                            No invitation email will be sent. Update your <i>.env</i> to send an email on invite.
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>

</x-app-layout>