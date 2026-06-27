<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminEventController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $events = Event::query()
            ->with(['organizer.user', 'venue'])
            ->withEventCounts()
            ->search($search)
            ->status($status)
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Event::query()->count(),
            'pending' => Event::query()->where('status', 'PENDING_APPROVAL')->count(),
            'approved' => Event::query()->where('status', 'APPROVED')->count(),
            'ongoing' => Event::query()->where('status', 'ONGOING')->count(),
        ];

        return view('admin.events.index', [
            'events' => $events,
            'search' => $search,
            'status' => $status,
            'stats' => $stats,
        ]);
    }

    public function show(Event $event): View
    {
        $event->load([
            'organizer.user',
            'venue',
            'approver',
            'registrations' => fn ($query) => $query
                ->with(['tenant.user'])
                ->latest('registered_at')
                ->limit(10),
            'slots' => fn ($query) => $query->orderBy('slot_number')->limit(40),
        ])->loadCount([
            'slots',
            'slots as booked_slots_count' => fn ($query) => $query->where('is_booked', true),
            'registrations',
        ]);

        return view('admin.events.show', [
            'event' => $event,
        ]);
    }

    public function approve(Request $request, Event $event): RedirectResponse
    {
        if ($event->status !== 'PENDING_APPROVAL') {
            return to_route('admin.events.show', $event)
                ->with('error', 'Hanya event yang menunggu persetujuan yang bisa disetujui.');
        }

        $event->update([
            'status' => 'APPROVED',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return to_route('admin.events.show', $event)
            ->with('status', 'Event berhasil disetujui.');
    }

    public function reject(Request $request, Event $event): RedirectResponse
    {
        if ($event->status !== 'PENDING_APPROVAL') {
            return to_route('admin.events.show', $event)
                ->with('error', 'Hanya event yang menunggu persetujuan yang bisa ditolak.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $event->update([
            'status' => 'REJECTED',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'rejection_reason' => $validated['reason'],
        ]);

        return to_route('admin.events.show', $event)
            ->with('status', 'Event berhasil ditolak.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return to_route('admin.events.index')
            ->with('status', 'Event berhasil dihapus.');
    }
}
