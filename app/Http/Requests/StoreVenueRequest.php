<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'lowest_price' => ['required', 'numeric', 'min:0'],
            'highest_price' => ['required', 'numeric', 'min:0', 'gte:lowest_price'],
            'available_from' => ['required', 'date'],
            'available_to' => ['required', 'date', 'after_or_equal:available_from'],
        ];
    }
}
