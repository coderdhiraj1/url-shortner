<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InvitationService
{
    public function create(
        User $inviter,
        string $email,
        Role $role,
        ?string $companyName = null
    ): Invitation {
        return DB::transaction(function () use (
            $inviter,
            $email,
            $role,
            $companyName
        ) {
            if ($inviter->isSuperAdmin()) {
                // superadmin creating new company while inviting

                $userExists = User::where('email', $email)->exists();

                if ($userExists) {
                    throw ValidationException::withMessages([
                        'email' => 'This email is already registered.',
                    ]);
                }

                $company = Company::create([
                    'name' => $companyName,
                    'created_by' => $inviter->id,
                ]);

                $companyId = $company->id;

            } else {
                // admin creating invite but with existing company

                $companyId = $inviter->company_id;

                $userExists = User::where('company_id', $companyId)
                    ->where('email', $email)
                    ->exists();

                if ($userExists) {
                    throw ValidationException::withMessages([
                        'email' => 'This user already belongs to the company.',
                    ]);
                }

                $pendingInvitation = Invitation::where('company_id', $companyId)
                    ->where('email', $email)
                    ->whereNull('accepted_at')
                    ->exists();

                if ($pendingInvitation) {
                    throw ValidationException::withMessages([
                        'email' => 'An invitation has already been sent to this email.',
                    ]);
                }
            }

            return Invitation::create([
                'company_id' => $companyId,
                'invited_by' => $inviter->id,
                'email' => $email,
                'role' => $role->value,
                'token' => Str::random(64),
                'expires_at' => now()->addDays(7),
            ]);
        });
    }
}