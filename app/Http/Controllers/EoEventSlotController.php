<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventOrganizer;
use App\Models\EventSlot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EoEventSlotController extends Controller
{
    public function index(Request $request, Event $event): View|RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        if (! $this->canManageSlots($event)) {
            return to_route('eo.events.show', $event)
                ->with('error', 'Slot hanya dapat dikelola untuk event berstatus APPROVED atau ONGOING.');
        }

        $slots = $event->slots()
            ->orderBy('slot_number')
            ->get();

        return view('eo.event-slots.index', [
            'event' => $event,
            'slots' => $slots,
        ]);
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($request, $event);
        $this->abortUnlessManageable($event);

        if ($request->has('types')) {
            $validated = $request->validate([
                'types' => ['required', 'array', 'min:1'],
                'types.*.prefix' => ['nullable', 'string', 'max:50'],
                'types.*.qty' => ['required', 'integer', 'min:1', 'max:500'],
                'types.*.width' => ['nullable', 'numeric', 'min:0'],
                'types.*.long' => ['nullable', 'numeric', 'min:0'],
                'types.*.price' => ['nullable', 'numeric', 'min:0'],
            ]);

            $created = $this->createBulkSlots($event, $validated['types']);

            return to_route('eo.events.slots.index', $event)
                ->with('status', "{$created} slot berhasil dibuat.");
        }

        $validated = $request->validate([
            'slot_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('event_slots', 'slot_number')
                    ->where(fn (Builder $query) => $query->where('event_id', $event->id))
                    ->withoutTrashed(),
            ],
            'slot_label' => ['nullable', 'string', 'max:100'],
            'slot_width' => ['nullable', 'numeric', 'min:0'],
            'slot_long' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $this->createSlot($event, $validated);

        return to_route('eo.events.slots.index', $event)
            ->with('status', 'Slot berhasil ditambahkan.');
    }

    public function update(Request $request, Event $event, EventSlot $eventSlot): RedirectResponse
    {
        $this->authorizeEvent($request, $event);
        $this->abortUnlessManageable($event);
        $this->abortUnlessEventSlot($event, $eventSlot);

        if ($eventSlot->is_booked) {
            return to_route('eo.events.slots.index', $event)
                ->with('error', 'Slot sudah dipesan dan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'slot_label' => ['nullable', 'string', 'max:100'],
            'slot_width' => ['nullable', 'numeric', 'min:0'],
            'slot_long' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $eventSlot->update([
            'slot_label' => $validated['slot_label'] ?? null,
            'slot_width' => $validated['slot_width'] ?? null,
            'slot_long' => $validated['slot_long'] ?? null,
            'price' => $validated['price'] ?? 0,
        ]);

        return to_route('eo.events.slots.index', $event)
            ->with('status', 'Slot berhasil diperbarui.');
    }

    public function destroy(Request $request, Event $event, EventSlot $eventSlot): RedirectResponse
    {
        $this->authorizeEvent($request, $event);
        $this->abortUnlessManageable($event);
        $this->abortUnlessEventSlot($event, $eventSlot);

        if ($eventSlot->is_booked) {
            return to_route('eo.events.slots.index', $event)
                ->with('error', 'Slot sudah dipesan dan tidak dapat dihapus.');
        }

        $eventSlot->delete();

        return to_route('eo.events.slots.index', $event)
            ->with('status', 'Slot berhasil dihapus.');
    }

    private function authorizeEvent(Request $request, Event $event): void
    {
        $organizer = EventOrganizer::forUserOrCreate($request->user());

        abort_unless($event->organizer_id === $organizer->id, 403);
    }

    private function canManageSlots(Event $event): bool
    {
        return in_array($event->status, ['APPROVED', 'ONGOING'], true);
    }

    private function abortUnlessManageable(Event $event): void
    {
        abort_unless($this->canManageSlots($event), 403);
    }

    private function abortUnlessEventSlot(Event $event, EventSlot $eventSlot): void
    {
        abort_unless($eventSlot->event_id === $event->id, 404);
    }

    /**
     * @param  array<int, array<string, mixed>>  $types
     */
    private function createBulkSlots(Event $event, array $types): int
    {
        $maxSlotNumber = (int) $event->slots()->max('slot_number');
        $created = 0;

        foreach ($types as $type) {
            $quantity = (int) ($type['qty'] ?? 0);
            $prefix = trim((string) ($type['prefix'] ?? ''));

            for ($index = 1; $index <= $quantity; $index++) {
                $maxSlotNumber++;
                $this->createSlot($event, [
                    'slot_number' => $maxSlotNumber,
                    'slot_label' => $prefix !== '' ? "{$prefix}-{$index}" : (string) $maxSlotNumber,
                    'slot_width' => $type['width'] ?? null,
                    'slot_long' => $type['long'] ?? null,
                    'price' => $type['price'] ?? 0,
                ]);
                $created++;
            }
        }

        return $created;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function createSlot(Event $event, array $payload): EventSlot
    {
        return EventSlot::create([
            'id' => (string) Str::uuid(),
            'event_id' => $event->id,
            'slot_number' => $payload['slot_number'],
            'slot_label' => $payload['slot_label'] ?? null,
            'date_start' => $event->event_start,
            'date_end' => $event->event_end,
            'slot_width' => $payload['slot_width'] ?? null,
            'slot_long' => $payload['slot_long'] ?? null,
            'price' => $payload['price'] ?? 0,
            'is_booked' => false,
        ]);
    }
}
