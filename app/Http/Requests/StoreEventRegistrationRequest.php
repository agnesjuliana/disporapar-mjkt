<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => ['required', 'exists:events,id'],
            'requested_slot_count' => ['required', 'integer', 'min:1', 'max:20'],
            'requested_slot_ids' => ['nullable', 'array'],
            'requested_slot_ids.*' => ['string', 'exists:event_slots,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
