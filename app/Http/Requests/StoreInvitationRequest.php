<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Role;
use Illuminate\Validation\Rule;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleRules = $this->user()?->isSuperAdmin() ? [Role::ADMIN->value] : [Role::ADMIN->value, Role::MEMBER->value];

        $rules = [
            'email' => [
                'required',
                'email',
            ],
            'role' => [
                'required',
                Rule::in($roleRules),
            ],
        ];

        if ($this->user()?->isSuperAdmin()) {
            $rules['company_name'] = [
                'required',
                'string',
                'max:255',
            ];
        }

        return $rules;
    }
}
