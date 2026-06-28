<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenueBookingRequest;
use App\Models\Event;
use App\Models\Venue;
use App\Models\VenueBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EoVenueBookingController extends EoBaseController
{
    public function venues(Request $request): View
    {
        $organizer = $this->resolveOrganizer($request);
        $search = $request->string('search')->toString();

        $venues = Venue::query()
            ->withCurrentBookings()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        $events = Event::query()
            ->where('organizer_id', $organizer->id)
            ->orderByDesc('event_start')
            ->get(['id', 'name', 'event_start']);

        // All active (PENDING/APPROVED) bookings for the datepicker conflict display
        $bookingsByVenue = VenueBooking::query()
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->where('booking_end', '>=', now())
            ->get(['venue_id', 'booking_start', 'booking_end', 'status'])
            ->groupBy('venue_id')
            ->map(fn ($group) => $group->map(fn ($b) => [
                'start'  => $b->booking_start->toIso8601String(),
                'end'    => $b->booking_end->toIso8601String(),
                'status' => $b->status,
            ])->values());

        return view('eo.venues.index', [
            'venues'          => $venues,
            'events'          => $events,
            'search'          => $search,
            'bookingsByVenue' => $bookingsByVenue,
        ]);
    }

    public function bookings(Request $request): View
    {
        $organizer = $this->resolveOrganizer($request);
        $status = $request->string('status')->toString();

        $bookings = VenueBooking::query()
            ->with(['venue', 'event'])
            ->where('organizer_id', $organizer->id)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('requested_at')
            ->paginate(10)
            ->withQueryString();

        return view('eo.venue-bookings.index', [
            'bookings' => $bookings,
            'status'   => $status,
        ]);
    }

    public function store(StoreVenueBookingRequest $request): RedirectResponse
    {
        $organizer = $this->resolveOrganizer($request);
        $validated = $request->validated();

        if (! empty($validated['event_id'])) {
            Event::query()
                ->where('id', $validated['event_id'])
                ->where('organizer_id', $organizer->id)
                ->firstOrFail();
        }

        // Reject if an active booking already overlaps the requested slot
        $conflict = VenueBooking::query()
            ->where('venue_id', $validated['venue_id'])
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->where('booking_start', '<', $validated['booking_end'])
            ->where('booking_end', '>', $validated['booking_start'])
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['booking_start' => 'Waktu yang dipilih sudah dipesan atau sedang dalam proses persetujuan. Silakan pilih waktu lain.'])
                ->withInput();
        }

        VenueBooking::create([
            'id'           => (string) Str::uuid(),
            'venue_id'     => $validated['venue_id'],
            'organizer_id' => $organizer->id,
            'event_id'     => $validated['event_id'] ?? null,
            'booking_start' => $validated['booking_start'],
            'booking_end'  => $validated['booking_end'],
            'booking_type' => $validated['booking_type'],
            'status'       => 'PENDING',
            'requested_at' => now(),
        ]);

        return to_route('eo.venue-booking')
            ->with('status', 'Permintaan booking venue berhasil dikirim.');
    }
}
