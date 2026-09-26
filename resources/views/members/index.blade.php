<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Team Members
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    View all members of your company.
                </p>
            </div>

            <a
                href="{{ route('invitations.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium
                    rounded-md hover:bg-indigo-700"
            >
                Invite
            </a>

        </div>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6">

                    <div class="overflow-x-auto">

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

                            <tbody class="divide-y divide-gray-200">

                                @forelse ($members as $index => $member)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $members->firstItem() + $index }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">

                                            <div class="flex items-center gap-2">

                                                <span>
                                                    {{ $member->name }}
                                                </span>

                                                @if ($member->id === auth()->id())
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-medium
                                                            text-indigo-600 bg-indigo-50
                                                            rounded-full"
                                                    >
                                                        You
                                                    </span>
                                                @endif

                                            </div>

                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $member->email }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ ucfirst($member->role->value) }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $member->short_urls_count }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $member->short_urls_sum_hits ?? 0 }}
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

                    <div class="flex items-center justify-between mt-6">

                        <p class="text-sm text-gray-500">
                            Showing {{ $members->firstItem() ?? 0 }}
                            to {{ $members->lastItem() ?? 0 }}
                            of {{ $members->total() }} results
                        </p>

                        {{ $members->links() }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>