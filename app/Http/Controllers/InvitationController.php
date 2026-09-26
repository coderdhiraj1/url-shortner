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
        $invitation = $invitationService->create(
            $request->user(),
            $request->string('email')->toString(),
            Role::from($request->string('role')->toString()),
            $request->input('company_name')
        );

        $msg = 'Invitation sent successfully.';

        $mailConfigured = config('mail.default') === 'smtp' && !empty(config('mail.mailers.smtp.host'));

        $msg = 'Invitation sent successfully.';

        if (!$mailConfigured) {
            $url = route('invitations.accept', $invitation->token);

            $msg = 'Invitation created successfully. Email service is not configured. '
                . '<a href="#" onclick="navigator.clipboard.writeText(\'' . $url . '\'); return false;" '
                . 'class="text-indigo-600 underline font-medium">'
                . 'Click to copy invitation URL'
                . '</a>';
        }

        return back()->with('success', $msg);
    }

    public function create(): View
    {
        $this->authorize('create', Invitation::class);
        return view('invitations.create');
    }

    public function accept(string $token): View
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();
        return view('invitations.accept', compact('invitation'));
    }
}
