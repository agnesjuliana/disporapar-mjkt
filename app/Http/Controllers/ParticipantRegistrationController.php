<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ParticipantRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ParticipantRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'MASYARAKAT', 403);

        $registrations = ParticipantRegistration::query()
            ->with(['event.organizer', 'event.venue'])
            ->where('user_id', $request->user()->id)
            ->latest('registered_at')
            ->paginate(10);

        return view('masyarakat.participant-registrations.index', [
            'registrations' => $registrations,
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        abort_unless($request->user()->role === 'MASYARAKAT', 403);

        $eventId = $request->string('event_id')->toString();
        $event = Event::query()
            ->with(['organizer', 'venue'])
            ->whereIn('status', ['APPROVED', 'ONGOING'])
            ->findOrFail($eventId);

        if ($event->event_end && $event->event_end->isPast()) {
            return to_route('event.calendar')
                ->with('error', 'Event sudah selesai dan tidak dapat menerima pendaftaran peserta.');
        }

        $existing = ParticipantRegistration::query()
            ->where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            return to_route('participant-registrations.show', $existing)
                ->with('status', 'Anda sudah terdaftar sebagai peserta event ini.');
        }

        return view('masyarakat.participant-registrations.create', [
            'event' => $event,
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'MASYARAKAT', 403);

        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'consent' => ['accepted'],
        ]);

        $event = Event::query()
            ->whereIn('status', ['APPROVED', 'ONGOING'])
            ->findOrFail($validated['event_id']);

        if ($event->event_end && $event->event_end->isPast()) {
            return to_route('event.calendar')
                ->with('error', 'Event sudah selesai dan tidak dapat menerima pendaftaran peserta.');
        }

        $existing = ParticipantRegistration::query()
            ->where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            return to_route('participant-registrations.show', $existing)
                ->with('status', 'Anda sudah terdaftar sebagai peserta event ini.');
        }

        $registration = ParticipantRegistration::create([
            'id' => (string) Str::uuid(),
            'event_id' => $event->id,
            'user_id' => $request->user()->id,
            'registered_at' => now(),
            'attendance_status' => 'NOT_CHECKED_IN',
        ]);

        return to_route('participant-registrations.show', $registration)
            ->with('status', 'Pendaftaran peserta berhasil. Simpan halaman ini sebagai bukti pendaftaran.');
    }

    public function show(Request $request, ParticipantRegistration $participantRegistration): View
    {
        abort_unless($request->user()->role === 'MASYARAKAT', 403);
        abort_unless($participantRegistration->user_id === $request->user()->id, 403);

        $participantRegistration->load(['event.organizer', 'event.venue', 'user']);

        return view('masyarakat.participant-registrations.show', [
            'registration' => $participantRegistration,
        ]);
    }

    public function edit(ParticipantRegistration $participantRegistration)
    {
        abort(404);
    }

    public function update(Request $request, ParticipantRegistration $participantRegistration)
    {
        abort(404);
    }

    public function destroy(ParticipantRegistration $participantRegistration)
    {
        abort(404);
    }
}
