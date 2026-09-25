<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvitationRequest;
use App\Services\InvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use App\Enums\Role;
use App\Models\Invitation;

class InvitationController extends Controller
{
    public function store(StoreInvitationRequest $request, InvitationService $invitationService ): RedirectResponse 
    {
        $this->authorize('create', Invitation::class);
        $invitationService->create(
            $request->user(),
            $request->string('email')->toString(),
            Role::from($request->string('role')->toString()),
            $request->input('company_name')
        );

        return back()->with('success', 'Invitation sent successfully.');
    }

    public function create(): View
    {
        return view('invitations.create');
    }

    public function accept(string $token): View
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        return view('invitations.accept', compact('invitation'));
    }
}
