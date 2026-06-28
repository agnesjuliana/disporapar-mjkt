<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlotAssignmentRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventSlot;
use App\Models\RegistrationSlot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EoTenantRegistrationController extends EoBaseController
{
    public function index(Request $request, Event $event): View
    {
        $this->authorizeEvent($request, $event);

        $status = $request->string('status')->toString();
        $search = $request->string('search')->toString();

        $registrations = $event->registrations()
            ->with(['tenant.user', 'slots.slot'])
            ->withCount('slots')
            ->where('registration_status', '!=', 'CANCELLED')
            ->when($status, fn (Builder $query) => $query->where('registration_status', $status))
            ->when($search, function (Builder $query) use ($search): void {
                $query->whereHas('tenant', function (Builder $tenantQuery) use ($search): void {
                    $tenantQuery
                        ->where('organization_name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%");
                });
            })
            ->latest('registered_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => $event->registrations()->where('registration_status', '!=', 'CANCELLED')->count(),
            'pending' => $event->registrations()->where('registration_status', 'PENDING')->count(),
            'approved' => $event->registrations()->where('registration_status', 'APPROVED')->count(),
            'assigned' => RegistrationSlot::query()
                ->whereHas('eventRegistration', fn (Builder $query) => $query->where('event_id', $event->id))
                ->where('status', 'ASSIGNED')
                ->count(),
        ];

        return view('eo.tenant-registrations.index', [
            'event' => $event,
            'registrations' => $registrations,
            'stats' => $stats,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function show(Request $request, Event $event, EventRegistration $registration): View
    {
        $this->authorizeEvent($request, $event);
        $this->abortUnlessEventRegistration($event, $registration);

        $registration->load(['tenant.user', 'approver', 'slots.slot']);

        $assignedSlotIds = $registration->slots->pluck('slot_id')->all();
        $preferredSlotIds = $registration->requested_slot_ids ?? [];

        $slots = $event->slots()
            ->with(['registrationSlots.eventRegistration.tenant'])
            ->orderBy('slot_number')
            ->get();

        return view('eo.tenant-registrations.show', [
            'event' => $event,
            'registration' => $registration,
            'slots' => $slots,
            'assignedSlotIds' => $assignedSlotIds,
            'preferredSlotIds' => $preferredSlotIds,
        ]);
    }

    public function approve(SlotAssignmentRequest $request, Event $event, EventRegistration $registration): RedirectResponse
    {
        $this->authorizeEvent($request, $event);
        $this->abortUnlessEventRegistration($event, $registration);

        $slotIds = array_values(array_unique($request->validated()['slot_ids'] ?? []));
        $result = $this->saveAssignment($request, $event, $registration, $slotIds, approve: true);

        return $result ?? to_route('eo.events.tenant-registrations.show', [$event, $registration])
            ->with('status', 'Pendaftaran tenant disetujui dan slot berhasil ditetapkan.');
    }

    public function assign(SlotAssignmentRequest $request, Event $event, EventRegistration $registration): RedirectResponse
    {
        $this->authorizeEvent($request, $event);
        $this->abortUnlessEventRegistration($event, $registration);

        $slotIds = array_values(array_unique($request->validated()['slot_ids'] ?? []));
        $result = $this->saveAssignment($request, $event, $registration, $slotIds, approve: false);

        return $result ?? to_route('eo.events.tenant-registrations.show', [$event, $registration])
            ->with('status', 'Penugasan slot berhasil diperbarui.');
    }

    public function reject(Request $request, Event $event, EventRegistration $registration): RedirectResponse
    {
        $this->authorizeEvent($request, $event);
        $this->abortUnlessEventRegistration($event, $registration);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $registration, $validated): void {
            $registration->load('slots');
            $slotIds = $registration->slots->pluck('slot_id')->all();

            if ($slotIds !== []) {
                EventSlot::query()
                    ->whereIn('id', $slotIds)
                    ->update(['is_booked' => false]);
            }

            $registration->slots()->delete();
            $registration->update([
                'registration_status' => 'REJECTED',
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
                'rejection_reason' => $validated['reason'],
            ]);
        });

        return to_route('eo.events.tenant-registrations.show', [$event, $registration])
            ->with('status', 'Pendaftaran tenant ditolak.');
    }

    /**
     * @param  array<int, string>  $slotIds
     */
    private function saveAssignment(Request $request, Event $event, EventRegistration $registration, array $slotIds, bool $approve): ?RedirectResponse
    {
        if ($registration->registration_status === 'REJECTED') {
            return to_route('eo.events.tenant-registrations.show', [$event, $registration])
                ->with('error', 'Pendaftaran yang sudah ditolak tidak dapat ditetapkan slotnya.');
        }

        $requestedCount = max((int) ($registration->requested_slot_count ?? 1), 1);
        $eventSlotCount = $event->slots()->count();

        if ($eventSlotCount > 0 && count($slotIds) < $requestedCount) {
            return to_route('eo.events.tenant-registrations.show', [$event, $registration])
                ->with('error', "Pilih minimal {$requestedCount} slot untuk tenant ini.");
        }

        $error = null;

        DB::transaction(function () use ($request, $event, $registration, $slotIds, $approve, &$error): void {
            $lockedRegistration = EventRegistration::query()
                ->whereKey($registration->id)
                ->lockForUpdate()
                ->firstOrFail();

            $currentSlotIds = $lockedRegistration->slots()
                ->pluck('slot_id')
                ->all();

            $selectedSlots = EventSlot::query()
                ->whereIn('id', $slotIds)
                ->lockForUpdate()
                ->get();

            if ($selectedSlots->count() !== count($slotIds)) {
                $error = 'Slot pilihan tidak valid.';
                return;
            }

            $invalidEventSlot = $selectedSlots->first(fn (EventSlot $slot) => $slot->event_id !== $event->id);
            if ($invalidEventSlot) {
                $error = 'Slot pilihan tidak berasal dari event ini.';
                return;
            }

            $takenSlot = $selectedSlots->first(fn (EventSlot $slot) => $slot->is_booked && ! in_array($slot->id, $currentSlotIds, true));
            if ($takenSlot) {
                $error = "Slot #{$takenSlot->slot_number} sudah dipakai tenant lain.";
                return;
            }

            if ($currentSlotIds !== []) {
                EventSlot::query()
                    ->whereIn('id', $currentSlotIds)
                    ->update(['is_booked' => false]);
                $lockedRegistration->slots()->delete();
            }

            foreach ($selectedSlots as $slot) {
                RegistrationSlot::create([
                    'id' => (string) Str::uuid(),
                    'event_registration_id' => $lockedRegistration->id,
                    'slot_id' => $slot->id,
                    'status' => 'ASSIGNED',
                    'assigned_at' => now(),
                ]);
            }

            if ($slotIds !== []) {
                EventSlot::query()
                    ->whereIn('id', $slotIds)
                    ->update(['is_booked' => true]);
            }

            if ($approve) {
                $lockedRegistration->update([
                    'registration_status' => 'APPROVED',
                    'approved_by' => $request->user()->id,
                    'approved_at' => now(),
                    'rejection_reason' => null,
                ]);
            }
        });

        if ($error) {
            return to_route('eo.events.tenant-registrations.show', [$event, $registration])
                ->withInput()
                ->with('error', $error);
        }

        return null;
    }
}
