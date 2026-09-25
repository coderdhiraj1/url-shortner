<x-guest-layout>
    <x-authentication-card>

        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4">
            <br>
            <h2 class="text-xl font-semibold text-gray-800">
                You're invited!
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                You have been invited to join
                <strong>{{ $invitation->company->name }}</strong>.
            </p>
        </div>

        <div class="mt-6 space-y-2 text-sm text-gray-600">
            <p>
                <span class="font-medium text-gray-800">Email:</span>
                {{ $invitation->email }}
            </p>

            <p>
                <span class="font-medium text-gray-800">Role:</span>
                {{ ucfirst($invitation->role) }}
            </p>
        </div>

        <div class="flex justify-center mt-6">
            <a
                href="{{ route('register', ['invitation' => $invitation->token, 'email' => $invitation->email]) }}"
                class="inline-flex items-center px-5 py-2
                    bg-indigo-600 border border-transparent
                    rounded-md font-semibold text-sm text-white
                    hover:bg-indigo-700
                    focus:outline-none focus:ring-2
                    focus:ring-indigo-500 focus:ring-offset-2"
            >
                {{ __('Accept & Sign Up') }}
            </a>
        </div>
        <br>

    </x-authentication-card>
</x-guest-layout>