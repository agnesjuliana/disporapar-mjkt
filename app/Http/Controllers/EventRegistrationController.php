<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventSlot;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $this->tenantFor($request);
        $status = $request->string('status')->toString();

        $registrations = EventRegistration::query()
            ->with(['event.venue', 'event.organizer'])
            ->withCount('slots')
            ->where('tenant_id', $tenant->id)
            ->where('registration_status', '!=', 'CANCELLED')
            ->when($status, fn (Builder $query) => $query->where('registration_status', $status))
            ->latest('registered_at')
            ->paginate(10)
            ->withQueryString();

        return view('tenant.registrations.index', [
            'registrations' => $registrations,
            'status' => $status,
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $tenant = $this->tenantFor($request);
        $eventId = $request->string('event_id')->toString();
        $event = Event::query()
            ->with(['organizer', 'venue'])
            ->withEventCounts()
            ->findOrFail($eventId);

        $guard = $this->guardRegistration($tenant, $event);
        if ($guard) {
            return $guard;
        }

        $reservedSlotIds = $this->reservedRequestedSlotIds($event);
        $event->setRelation('slots', $event->slots()
            ->where('is_booked', false)
            ->when($reservedSlotIds !== [], fn (Builder $query) => $query->whereNotIn('id', $reservedSlotIds))
            ->orderBy('slot_number')
            ->get());

        return view('tenant.registrations.create', [
            'tenant' => $tenant,
            'event' => $event,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = $this->tenantFor($request);
        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'requested_slot_count' => ['required', 'integer', 'min:1', 'max:20'],
            'requested_slot_ids' => ['nullable', 'array'],
            'requested_slot_ids.*' => ['string', 'exists:event_slots,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $event = Event::query()
            ->with(['slots' => fn ($query) => $query->where('is_booked', false)])
            ->withEventCounts()
            ->findOrFail($validated['event_id']);

        $guard = $this->guardRegistration($tenant, $event);
        if ($guard) {
            return $guard;
        }

        $requestedSlotIds = array_values(array_unique($validated['requested_slot_ids'] ?? []));
        $reservedSlotIds = $this->reservedRequestedSlotIds($event);
        $blockedSlotIds = array_intersect($requestedSlotIds, $reservedSlotIds);

        if ($blockedSlotIds !== []) {
            return to_route('event-registrations.create', ['event_id' => $event->id])
                ->withInput()
                ->with('error', 'Sebagian slot pilihan sedang diproses oleh tenant lain.');
        }

        $validRequestedSlotIds = EventSlot::query()
            ->where('event_id', $event->id)
            ->where('is_booked', false)
            ->whereIn('id', $requestedSlotIds)
            ->pluck('id')
            ->all();

        if (count($validRequestedSlotIds) !== count($requestedSlotIds)) {
            return to_route('event-registrations.create', ['event_id' => $event->id])
                ->withInput()
                ->with('error', 'Slot pilihan tidak valid atau sudah tidak tersedia.');
        }

        if (count($validRequestedSlotIds) > (int) $validated['requested_slot_count']) {
            return to_route('event-registrations.create', ['event_id' => $event->id])
                ->withInput()
                ->with('error', 'Jumlah slot pilihan tidak boleh melebihi jumlah tenant/slot yang diminta.');
        }

        $availableSlots = EventSlot::query()
            ->where('event_id', $event->id)
            ->where('is_booked', false)
            ->when($reservedSlotIds !== [], fn (Builder $query) => $query->whereNotIn('id', $reservedSlotIds))
            ->count();
        if ($availableSlots > 0 && (int) $validated['requested_slot_count'] > $availableSlots) {
            return to_route('event-registrations.create', ['event_id' => $event->id])
                ->withInput()
                ->with('error', 'Jumlah tenant/slot yang diminta melebihi slot tersedia.');
        }

        $registration = EventRegistration::create([
            'id' => (string) Str::uuid(),
            'event_id' => $event->id,
            'tenant_id' => $tenant->id,
            'registration_status' => 'PENDING',
            'registered_at' => now(),
            'requested_slot_count' => (int) $validated['requested_slot_count'],
            'requested_slot_ids' => $validRequestedSlotIds,
            'notes' => $validated['notes'] ?? null,
        ]);

        return to_route('event-registrations.show', $registration)
            ->with('status', "Booking tenant untuk '{$event->name}' berhasil dikirim. Menunggu persetujuan Event Organizer.");
    }

    public function show(Request $request, EventRegistration $eventRegistration): View
    {
        $tenant = $this->tenantFor($request);
        abort_unless($eventRegistration->tenant_id === $tenant->id, 403);

        $eventRegistration->load([
            'event.venue',
            'event.organizer',
            'event.slots',
            'approver',
            'slots.slot',
            'attendance',
        ]);

        return view('tenant.registrations.show', [
            'registration' => $eventRegistration,
        ]);
    }

    public function cancel(Request $request, EventRegistration $eventRegistration): RedirectResponse
    {
        $tenant = $this->tenantFor($request);
        abort_unless($eventRegistration->tenant_id === $tenant->id, 403);

        if ($eventRegistration->registration_status !== 'PENDING') {
            return to_route('event-registrations.show', $eventRegistration)
                ->with('error', 'Booking hanya dapat dibatalkan saat masih menunggu persetujuan.');
        }

        DB::transaction(function () use ($eventRegistration): void {
            $eventRegistration->load('slots');

            $slotIds = $eventRegistration->slots->pluck('slot_id')->filter()->all();
            if ($slotIds !== []) {
                EventSlot::query()
                    ->whereIn('id', $slotIds)
                    ->update(['is_booked' => false]);
            }

            $eventRegistration->slots()->delete();
            $eventRegistration->delete();
        });

        return to_route('event-registrations.index')
            ->with('status', 'Booking tenant berhasil dibatalkan.');
    }

    private function tenantFor(Request $request): Tenant
    {
        return Tenant::query()
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
    }

    private function guardRegistration(Tenant $tenant, Event $event): ?RedirectResponse
    {
        if ($tenant->registration_status !== 'APPROVED') {
            return to_route('events.index')
                ->with('error', 'Akun tenant Anda belum diverifikasi.');
        }

        if ($event->status !== 'APPROVED') {
            return to_route('events.index')
                ->with('error', 'Event tidak valid untuk booking tenant.');
        }

        if ($event->registration_deadline && $event->registration_deadline->isPast()) {
            return to_route('events.show', $event)
                ->with('error', 'Batas waktu pendaftaran event sudah lewat.');
        }

        $alreadyRegistered = EventRegistration::query()
            ->where('tenant_id', $tenant->id)
            ->where('event_id', $event->id)
            ->where('registration_status', '!=', 'CANCELLED')
            ->exists();

        if ($alreadyRegistered) {
            return to_route('events.show', $event)
                ->with('error', 'Anda sudah membuat booking tenant untuk event ini.');
        }

        $slotCounts = $event->loadCount([
            'slots',
            'slots as booked_slots_count' => fn (Builder $query) => $query->where('is_booked', true),
        ]);

        if ($slotCounts->slots_count > 0 && $slotCounts->booked_slots_count >= $slotCounts->slots_count) {
            return to_route('events.show', $event)
                ->with('error', 'Semua slot event sudah terisi.');
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    private function reservedRequestedSlotIds(Event $event): array
    {
        return EventRegistration::query()
            ->where('event_id', $event->id)
            ->whereIn('registration_status', ['PENDING', 'APPROVED'])
            ->get(['requested_slot_ids'])
            ->flatMap(fn (EventRegistration $registration) => $registration->requested_slot_ids ?? [])
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
