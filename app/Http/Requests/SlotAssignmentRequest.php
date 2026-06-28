<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlotAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slot_ids' => ['nullable', 'array'],
            'slot_ids.*' => ['string', 'exists:event_slots,id'],
        ];
    }
}
