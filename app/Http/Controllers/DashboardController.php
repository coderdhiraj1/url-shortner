<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $companies = collect();
        $teamMembers = collect();

        if ($user->isSuperAdmin()) {
            $companies = Company::withCount('users')->get();
        }

        if ($user->isAdmin()) {
            $teamMembers = $user->company
                ->users()
                ->get();
        }

        return view('dashboard', compact(
            'companies',
            'teamMembers'
        ));
    }
}