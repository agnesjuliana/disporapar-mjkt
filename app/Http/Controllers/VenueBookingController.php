<?php

namespace App\Http\Controllers;

use App\Models\VenueBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VenueBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $bookings = VenueBooking::query()
            ->with(['venue', 'organizer.user', 'event'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('requested_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.venue-bookings.index', [
            'bookings' => $bookings,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(VenueBooking $venueBooking): View
    {
        $venueBooking->load(['venue', 'organizer.user', 'event', 'approver']);

        return view('admin.venue-bookings.show', [
            'booking' => $venueBooking,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VenueBooking $venueBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VenueBooking $venueBooking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VenueBooking $venueBooking)
    {
        //
    }

    public function approve(Request $request, VenueBooking $venueBooking): RedirectResponse
    {
        $validated = $request->validate([
            'final_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $venueBooking->update([
            'status' => 'APPROVED',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'final_price' => $validated['final_price'] ?? null,
        ]);

        return redirect()
            ->route('admin.venue-bookings.index')
            ->with('status', 'Booking venue berhasil disetujui.');
    }

    public function reject(Request $request, VenueBooking $venueBooking): RedirectResponse
    {
        $venueBooking->update([
            'status' => 'REJECTED',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('admin.venue-bookings.index')
            ->with('status', 'Booking venue berhasil ditolak.');
    }
}
