<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:255'],
            'org_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
            // Minat kategori hanya wajib untuk registrasi tenant (dipakai untuk
            // rekomendasi event), bukan untuk registrasi event organizer.
            'interest_category_ids' => [$this->routeIs('register.tenant.store') ? 'required' : 'nullable', 'array', 'min:1'],
            'interest_category_ids.*' => ['exists:categories,id'],
        ];
    }
}
