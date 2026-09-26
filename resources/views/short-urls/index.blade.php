<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Short URLs
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    View generated short URLs.
                </p>
            </div>

            @if (auth()->user()->isAdmin() || auth()->user()->isMember())
                <a
                    href="{{ route('short-urls.create') }}"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium
                        rounded-md hover:bg-indigo-700"
                >
                    Create Short URL
                </a>
            @endif

        </div>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800"></h3>
                            <p class="mt-1 text-sm text-gray-500"></p>
                        </div>

                        <div class="flex items-center gap-3">
                            <form
                                method="GET"
                                action="{{ route('short-urls.index') }}"
                                class="flex items-center gap-3"
                            >
                                <select
                                    name="filter"
                                    onchange="this.form.submit()"
                                    class="rounded-md border-gray-300 text-sm
                                        focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="all" @selected($filter === 'all')>
                                        Show all
                                    </option>

                                    <option value="today" @selected($filter === 'today')>
                                        Today
                                    </option>

                                    <option value="this_week" @selected($filter === 'this_week')>
                                        This Week
                                    </option>

                                    <option value="this_month" @selected($filter === 'this_month')>
                                        This Month
                                    </option>

                                    <option value="last_month" @selected($filter === 'last_month')>
                                        Last Month
                                    </option>
                                </select>

                                @if ($shortUrls->count() > 0)
                                <a
                                    href="{{ route('short-urls.download', ['filter' => $filter]) }}"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium
                                        rounded-md hover:bg-indigo-700"
                                >
                                    Download
                                </a>
                                @else
                                <a
                                    href="javascript:void(0); alert('Nothing to download!')"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium
                                        rounded-md "
                                >
                                    Download
                                </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="overflow-x-auto">

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
                                        {{ auth()->user()->isSuperAdmin() ? 'Company' : 'Created By' }}
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 uppercase">
                                        Created On
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @forelse ($shortUrls as $index => $shortUrl)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $shortUrls->firstItem() + $index }}
                                        </td>

                                        <td class="px-6 py-4 text-sm">

                                            <a
                                                href="{{ url('/s/' . $shortUrl->short_code) }}"
                                                target="_blank"
                                                class="text-indigo-600 hover:text-indigo-800 font-medium"
                                            >
                                                {{ url('/s/' . $shortUrl->short_code) }}
                                            </a>

                                        </td>

                                        <td
                                            class="px-6 py-4 text-sm text-gray-700"
                                            title="{{ $shortUrl->original_url }}"
                                        >
                                            {{ \Illuminate\Support\Str::limit($shortUrl->original_url, 60, '...') }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $shortUrl->hits }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">

                                            @if (auth()->user()->isSuperAdmin())
                                                {{ $shortUrl->company->name }}
                                            @else
                                                {{ $shortUrl->creator->name }}
                                            @endif

                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $shortUrl->created_at->format('d M Y') }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="6"
                                            class="px-6 py-8 text-center text-sm text-gray-500"
                                        >
                                            No short URLs found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="flex items-center justify-between mt-6">

                        <p class="text-sm text-gray-500">
                            Showing {{ $shortUrls->firstItem() ?? 0 }}
                            to {{ $shortUrls->lastItem() ?? 0 }}
                            of {{ $shortUrls->total() }} results
                        </p>

                        {{ $shortUrls->links() }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>