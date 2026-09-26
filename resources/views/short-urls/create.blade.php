<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Short URL') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6">

                    {{-- Header --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Create Short URL
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Enter a long URL to generate a short URL.
                        </p>
                    </div>

                    {{-- Form --}}
                    <form
                        method="POST"
                        action="{{ route('short-urls.store') }}"
                    >
                        @csrf

                        {{-- Original URL --}}
                        <div>
                            <label
                                for="original_url"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Original URL
                            </label>

                            <input
                                id="original_url"
                                name="original_url"
                                type="url"
                                value="{{ old('original_url') }}"
                                placeholder="https://example.com/your-long-url"
                                required
                                autofocus
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                    focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('original_url')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center justify-end mt-6">

                            <a
                                href="{{ route('dashboard') }}"
                                class="px-4 py-2 mr-3 text-sm font-medium
                                       text-gray-700 bg-gray-100 rounded-md
                                       hover:bg-gray-200"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white
                                    text-sm font-medium rounded-md
                                    hover:bg-indigo-700
                                    focus:outline-none focus:ring-2
                                    focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Generate Short URL
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>