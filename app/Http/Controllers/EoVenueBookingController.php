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

        return view('eo.venues.index', [
            'venues' => $venues,
            'events' => $events,
            'search' => $search,
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
            'status' => $status,
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

        VenueBooking::create([
            'id' => (string) Str::uuid(),
            'venue_id' => $validated['venue_id'],
            'organizer_id' => $organizer->id,
            'event_id' => $validated['event_id'] ?? null,
            'booking_start' => $validated['booking_start'],
            'booking_end' => $validated['booking_end'],
            'status' => 'PENDING',
            'requested_at' => now(),
        ]);

        return to_route('eo.venue-booking')
            ->with('status', 'Permintaan booking venue berhasil dikirim.');
    }
}
