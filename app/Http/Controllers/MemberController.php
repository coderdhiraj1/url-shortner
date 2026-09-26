<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        abort_unless($user->isAdmin(), 403);

        $members = $user->company
            ->users()
            ->withCount('shortUrls')
            ->withSum('shortUrls', 'hits')
            ->paginate(config('pagination.fullview_limit'));

        return view('members.index', compact('members'));
    }
}