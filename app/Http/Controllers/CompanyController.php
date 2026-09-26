<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);
        
        $companies = Company::withCount([
                'users',
                'shortUrls',
            ])
            ->withSum('shortUrls', 'hits')
            ->with('adminInvitation')
            ->paginate(config('pagination.fullview_limit'));

        return view('companies.index', compact('companies'));
    }
}