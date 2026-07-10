<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ParticipantRegistration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EoParticipantRegistrationController extends EoBaseController
{
    public function index(Request $request, Event $event): View
    {
        $this->authorizeEvent($request, $event);

        $search = $request->string('search')->toString();
        $attendanceStatus = $request->string('attendance_status')->toString();

        $registrations = ParticipantRegistration::query()
            ->with('user')
            ->where('event_id', $event->id)
            ->when($search, function (Builder $query) use ($search): void {
                $query->whereHas('user', function (Builder $userQuery) use ($search): void {
                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($attendanceStatus, fn (Builder $query) => $query->where('attendance_status', $attendanceStatus))
            ->latest('registered_at')
            ->paginate(10)
            ->withQueryString();

        $baseStats = ParticipantRegistration::query()->where('event_id', $event->id);

        $stats = [
            'total' => (clone $baseStats)->count(),
            'not_checked_in' => (clone $baseStats)->where('attendance_status', 'NOT_CHECKED_IN')->count(),
            'present' => (clone $baseStats)->where('attendance_status', 'PRESENT')->count(),
            'absent' => (clone $baseStats)->where('attendance_status', 'ABSENT')->count(),
        ];

        $stats['attendance_rate'] = $stats['total'] > 0
            ? round(($stats['present'] / $stats['total']) * 100)
            : 0;

        return view('eo.participant-registrations.index', [
            'event' => $event,
            'registrations' => $registrations,
            'stats' => $stats,
            'search' => $search,
            'attendanceStatus' => $attendanceStatus,
        ]);
    }

    public function checkIn(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $registration = ParticipantRegistration::query()
            ->with('user')
            ->find($validated['code']);

        if (! $registration || $registration->event_id !== $event->id) {
            return back()->with('error', 'Kode QR tidak dikenali atau bukan untuk event ini.');
        }

        if ($registration->attendance_status === 'PRESENT') {
            return back()->with('error', "Peserta {$registration->user?->name} sudah check-in sebelumnya pada {$registration->checked_in_at?->format('d M Y H:i')}.");
        }

        $registration->update([
            'attendance_status' => 'PRESENT',
            'checked_in_at' => now(),
        ]);

        return back()->with('status', "Check-in berhasil untuk {$registration->user?->name}.");
    }
}
