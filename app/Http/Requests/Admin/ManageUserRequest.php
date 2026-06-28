<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ManageUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => [$user ? 'sometimes' : 'required', Rule::in(['ADMIN', 'EVENT_ORGANIZER', 'TENANT', 'MASYARAKAT'])],
            'status' => ['required', Rule::in(['ACTIVE', 'INACTIVE', 'SUSPENDED'])],
            'is_verified' => ['nullable', 'boolean'],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:255'],
            'org_name' => ['nullable', 'required_if:role,EVENT_ORGANIZER,TENANT', 'string', 'max:255'],
            'address' => ['nullable', 'required_if:role,EVENT_ORGANIZER,TENANT', 'string', 'max:255'],
        ];
    }
}
