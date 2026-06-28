<?php

namespace App\Http\Controllers;

use App\Models\VenueBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VenueBookingController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $stats = [
            'total' => VenueBooking::query()->count(),
            'pending' => VenueBooking::query()->where('status', 'PENDING')->count(),
            'approved' => VenueBooking::query()->where('status', 'APPROVED')->count(),
            'rejected' => VenueBooking::query()->where('status', 'REJECTED')->count(),
            'cancelled' => VenueBooking::query()->where('status', 'CANCELLED')->count(),
        ];

        $bookings = VenueBooking::query()
            ->with(['venue', 'organizer.user', 'event'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('requested_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.venue-bookings.index', [
            'bookings' => $bookings,
            'status' => $status,
            'stats' => $stats,
        ]);
    }

    public function show(VenueBooking $venueBooking): View
    {
        $venueBooking->load(['venue', 'organizer.user', 'event', 'approver']);

        return view('admin.venue-bookings.show', [
            'booking' => $venueBooking,
        ]);
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

        return to_route('admin.venue-bookings.index')
            ->with('status', 'Booking venue berhasil disetujui.');
    }

    public function reject(Request $request, VenueBooking $venueBooking): RedirectResponse
    {
        $venueBooking->update([
            'status' => 'REJECTED',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return to_route('admin.venue-bookings.index')
            ->with('status', 'Booking venue berhasil ditolak.');
    }
}
