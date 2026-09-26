@php
    use Illuminate\Support\Str;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
            @if (!auth()->user()->isSuperAdmin() && auth()->user()->company)
            <span class="text-sm text-gray-500">
                ({{ auth()->user()->company->name }})
            </span>
            @endif
            @if(auth()->user()->isSuperAdmin())
            <span class="text-sm text-gray-500">
                (Superadmin)
            </span>
            @endif
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

                                @forelse ($companies->take(config('pagination.dashboard_limit')) as $index => $company)
                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $company->name }}
                                            <br>
                                            <small>({{ $company->invitations->first()?->email ?? '-' }})</small>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $company->users_count }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $company->short_urls_count }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $company->short_urls_sum_hits ?? 0 }}
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

                        <div class="flex items-center justify-between mt-4">
                            <p class="text-sm text-gray-500">
                                Showing {{ min(config('pagination.dashboard_limit'), $companies->count()) }}
                                of {{ $companies->count() }} clients
                            </p>

                            @if ($companies->count() > config('pagination.dashboard_limit'))
                                <a
                                    href="{{ route('companies.index') }}"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                                >
                                    View All
                                </a>
                            @endif
                        </div>

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

                        @if (auth()->user()->isAdmin() || auth()->user()->isMember())
                        <a href="{{ route('short-urls.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Create Short URL
                        </a>
                        @endif



                        <div class="flex items-center gap-3">
                            <form
                                method="GET"
                                action="{{ route('dashboard') }}"
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

                            <tbody class="divide-y divide-gray-200">
                            @forelse ($shortUrls->take(config('pagination.dashboard_limit')) as $index => $shortUrl)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <a
                                            href="{{ url('/s/'.$shortUrl->short_code) }}"
                                            target="_blank"
                                            class="text-indigo-600 hover:text-indigo-800 font-medium"
                                        >
                                            {{ url('/s/'.$shortUrl->short_code) }}
                                        </a>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700" title="{{ $shortUrl->original_url }}" >
                                        {{ Str::limit($shortUrl->original_url, 40, '...') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $shortUrl->hits }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @if(auth()->user()->isSuperAdmin())
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

                        <div class="flex items-center justify-between mt-4">
                            <p class="text-sm text-gray-500">
                                Showing {{ min(config('pagination.dashboard_limit'), $shortUrls->count()) }}
                                of {{ $shortUrls->count() }} URLs
                            </p>

                            @if ($shortUrls->count() > config('pagination.dashboard_limit'))
                                <a
                                    href="{{ route('short-urls.index') }}"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                                >
                                    View All
                                </a>
                            @endif
                        </div>

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

                                @forelse ($teamMembers->take(config('pagination.dashboard_limit')) as $index => $member)

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

                        <div class="flex items-center justify-between mt-4">
                            <p class="text-sm text-gray-500">
                                Showing {{ min(config('pagination.dashboard_limit'), $teamMembers->count()) }}
                                of {{ $teamMembers->count() }} members
                            </p>

                            @if ($teamMembers->count() > config('pagination.dashboard_limit'))
                                <a
                                    href="{{ route('members.index') }}"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                                >
                                    View All
                                </a>
                            @endif
                        </div>

                    </div>

                </div>

            </div>
            @endif

        </div>
    </div>
</x-app-layout>