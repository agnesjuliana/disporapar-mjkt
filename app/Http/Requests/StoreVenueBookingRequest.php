<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVenueBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'venue_id' => ['required', 'exists:venues,id'],
            'event_id' => ['nullable', 'exists:events,id'],
            'booking_start' => ['required', 'date'],
            'booking_end' => ['required', 'date', 'after:booking_start'],
        ];
    }
}
