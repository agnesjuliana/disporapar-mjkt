<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'venue_type' => ['required', Rule::in(['INTERNAL', 'EXTERNAL'])],
            'venue_id' => ['nullable', 'required_if:venue_type,INTERNAL', 'exists:venues,id'],
            'external_venue_name' => ['nullable', 'required_if:venue_type,EXTERNAL', 'string', 'max:255'],
            'external_venue_address' => ['nullable', 'string', 'max:255'],
            'external_venue_capacity' => ['nullable', 'integer', 'min:0'],
            'event_start' => ['required', 'date'],
            'event_end' => ['required', 'date', 'after:event_start'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:event_start'],
            'slot_size' => ['nullable', 'integer', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'banner' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
