<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortUrlRequest;
use App\Models\ShortUrl;
use App\Services\ShortUrlService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShortUrlController extends Controller
{
    public function create(): View
    {
        $this->authorize('create', ShortUrl::class);
        return view('short-urls.create');
    }

    public function store(StoreShortUrlRequest $request, ShortUrlService $shortUrlService): RedirectResponse 
    {
        $this->authorize('create', ShortUrl::class);
        $shortUrlService->create(
            $request->user(),
            $request->string('original_url')->toString()
        );

        return redirect()->route('dashboard')->with('success', 'Short URL created successfully.');
    }

    public function redirect(string $shortCode): RedirectResponse
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)
            ->firstOrFail();

        $shortUrl->increment('hits');

        return redirect()->away($shortUrl->original_url);
    }

    public function index(Request $request): View
    {
        $user = auth()->user();

        $filter = $request->input('filter', 'all');

        if ($user->isSuperAdmin()) {

            $shortUrls = ShortUrl::with(['company', 'creator'])
                ->filterByDate($filter)
                ->latest()
                ->paginate(config('pagination.fullview_limit'));

        } elseif ($user->isAdmin()) {

            $shortUrls = ShortUrl::with('creator')
                ->where('company_id', $user->company_id)
                ->filterByDate($filter)
                ->latest()
                ->paginate(config('pagination.fullview_limit'));

        } else {

            $shortUrls = ShortUrl::with('creator')
                ->where('company_id', $user->company_id)
                ->where('created_by', $user->id)
                ->filterByDate($filter)
                ->latest()
                ->paginate(config('pagination.fullview_limit'));
        }

        return view('short-urls.index', compact(
            'shortUrls',
            'filter'
        ));
    }

    public function download(Request $request)
    {
        $user = auth()->user();
        $filter = $request->input('filter', 'all');

        $query = ShortUrl::with(
            $user->isSuperAdmin()
                ? ['company', 'creator']
                : ['creator']
        );

        if ($user->isSuperAdmin()) {
            // All URLs
        } elseif ($user->isAdmin()) {
            $query->where('company_id', $user->company_id);
        } else {
            $query->where('company_id', $user->company_id)
                ->where('created_by', $user->id);
        }

        $shortUrls = $query
            ->filterByDate($filter)
            ->latest()
            ->get();

        return response()->streamDownload(function () use ($shortUrls, $user) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Short URL',
                'Long URL',
                'Hits',
                $user->isSuperAdmin() ? 'Company' : 'Created By',
                'Created On',
            ]);

            foreach ($shortUrls as $shortUrl) {
                fputcsv($file, [
                    url('/s/' . $shortUrl->short_code),
                    $shortUrl->original_url,
                    $shortUrl->hits,
                    $user->isSuperAdmin()
                        ? $shortUrl->company->name
                        : $shortUrl->creator->name,
                    $shortUrl->created_at->format('d M Y'),
                ]);
            }

            fclose($file);

        }, 'short-urls.csv');
    }
}
