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
            'venue_id'     => ['required', 'exists:venues,id'],
            'event_id'     => ['nullable', 'exists:events,id'],
            'booking_type' => ['required', 'in:HOURLY,DAILY'],
            'booking_start' => ['required', 'date', 'after_or_equal:now'],
            'booking_end'  => ['required', 'date', 'after:booking_start'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking_start.after_or_equal' => 'Waktu mulai booking tidak boleh di masa lalu.',
            'booking_end.after'            => 'Waktu selesai harus setelah waktu mulai.',
            'booking_type.in'              => 'Tipe booking tidak valid.',
        ];
    }
}
