<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ShortUrl;
use Illuminate\View\View;
use App\Enums\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $filter = $request->input('filter', 'all');

        $companies = collect();
        $teamMembers = collect();
        $shortUrls = collect();

        if ($user->isSuperAdmin()) {
            $companies = Company::withCount(['users','shortUrls'])
                ->withSum('shortUrls', 'hits')
                ->with([
                    'invitations' => function ($query) {
                        $query->where('role', Role::ADMIN->value)
                        ->oldest('id');
                    },
                ])
                ->get();
            $shortUrls = ShortUrl::with(['company', 'creator'])
                ->filterByDate($filter)
                ->get();
        }

        if ($user->isAdmin()) {
            $teamMembers = $user->company
                ->users()
                ->withCount('shortUrls')
                ->withSum('shortUrls', 'hits')
                ->get();
            $shortUrls = ShortUrl::with('creator')
                ->where('company_id', $user->company_id)
                ->filterByDate($filter)
                ->get();
        }

        if ($user->isMember()) {
            $shortUrls = ShortUrl::with('creator')
                ->where('company_id', $user->company_id)
                ->where('created_by', $user->id)
                ->filterByDate($filter)
                ->get();
        }

        return view('dashboard', compact(
            'companies',
            'teamMembers',
            'shortUrls',
            'filter'
        ));
    }
}