<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- clients -->
            @if (auth()->user()->isSuperAdmin())
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Clients
                        </h3>

                        <a
                            href="{{ route('invitations.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
                        >
                            Invite
                        </a>
                    </div>

                    <div class="overflow-x-auto mt-4">
                        <table class="w-full border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="w-16 px-6 py-4 text-sm font-medium text-gray-500 uppercase">
                                        #
                                    </th>

                                    <th class="w-2/5 px-6 py-4 text-sm font-medium text-gray-500 uppercase">
                                        Client Name
                                    </th>

                                    <th class="w-1/6 px-6 py-4 text-sm font-medium text-gray-500 uppercase">
                                        Users
                                    </th>

                                    <th class="w-1/4 px-6 py-4 text-sm font-medium text-gray-500 uppercase">
                                        Total Generated URLs
                                    </th>

                                    <th class="w-1/4 px-6 py-4 text-sm font-medium text-gray-500 uppercase">
                                        Total URL Hits
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @forelse ($companies as $index => $company)
                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $company->name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $company->users_count }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            0
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            0
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                            No companies found.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
            @endif 

            <!-- generated short urls -->
            <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Generated Short URLs
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                View generated short URLs.
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <select
                                class="rounded-md border-gray-300 text-sm
                                    focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option>This Month</option>
                                <option>Last Month</option>
                                <option>Last Week</option>
                                <option>Today</option>
                            </select>

                            <button
                                type="button"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm
                                    font-medium rounded-md hover:bg-indigo-700"
                            >
                                Download
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto mt-6">

                        <table class="w-full border border-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        #
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Short URL
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Long URL
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Hits
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Created By
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Created On
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr class="hover:bg-gray-50">

                                    <td class="border-b border-gray-200 px-6 py-4 text-sm text-gray-700">
                                        1
                                    </td>

                                    <td class="border-b border-gray-200 px-6 py-4 text-sm">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-800">
                                            /abc123
                                        </a>
                                    </td>

                                    <td class="border-b border-gray-200 px-6 py-4 text-sm text-gray-700">
                                        https://google.com
                                    </td>

                                    <td class="border-b border-gray-200 px-6 py-4 text-sm text-gray-700">
                                        25
                                    </td>

                                    <td class="border-b border-gray-200 px-6 py-4 text-sm text-gray-700">
                                        Super Admin
                                    </td>

                                    <td class="border-b border-gray-200 px-6 py-4 text-sm text-gray-700">
                                        26 Sep 2026
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <!-- team members -->
            @if (auth()->user()->isAdmin())
            <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6">

                    {{-- Card Header --}}
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Team Members
                        </h3>

                        <a
                            href="{{ route('invitations.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
                        >
                            Invite
                        </a>
                    </div>

                    {{-- Team Members Table --}}
                    <div class="overflow-x-auto mt-6">

                        <table class="w-full border border-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        #
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Name 
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Email
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Role
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Total Generated URLs
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Total URL Hits
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($teamMembers as $index => $member)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $member->name }}
                                            @if ($member->id === auth()->id())
                                                <span>
                                                    (You)
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $member->email }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ ucfirst($member->role->value) }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            0
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            0
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="6"
                                            class="px-6 py-8 text-center text-sm text-gray-500"
                                        >
                                            No team members found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
            @endif

        </div>
    </div>
</x-app-layout>