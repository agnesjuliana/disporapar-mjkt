<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Models\Venue;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EoEventController extends EoBaseController
{
    use HandlesImageUpload;

    public function index(Request $request): View
    {
        $organizer = $this->resolveOrganizer($request);
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $events = Event::query()
            ->with('venue')
            ->withEventCounts()
            ->forOrganizer($organizer->id)
            ->search($search)
            ->status($status)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('eo.events.index', [
            'events' => $events,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('eo.events.create', [
            'event' => new Event(['venue_type' => 'INTERNAL', 'status' => 'DRAFT']),
            'venues' => Venue::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $organizer = $this->resolveOrganizer($request);

        $event = Event::create([
            'id' => (string) Str::uuid(),
            'organizer_id' => $organizer->id,
            'status' => 'DRAFT',
            ...$this->eventPayload($request->validated(), $request),
        ]);

        return to_route('eo.events.show', $event)
            ->with('status', "Event '{$event->name}' berhasil dibuat sebagai draft.");
    }

    public function show(Request $request, Event $event): View
    {
        $this->authorizeEvent($request, $event);

        $event->load([
            'venue',
            'approver',
            'registrations' => fn ($query) => $query
                ->with('tenant.user')
                ->latest('registered_at')
                ->limit(10),
            'slots' => fn ($query) => $query->orderBy('slot_number')->limit(40),
        ])->loadCount([
            'slots',
            'slots as booked_slots_count' => fn ($query) => $query->where('is_booked', true),
            'registrations',
        ]);

        return view('eo.events.show', [
            'event' => $event,
        ]);
    }

    public function edit(Request $request, Event $event): View|RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        if (! in_array($event->status, ['DRAFT', 'REJECTED'], true)) {
            return to_route('eo.events.show', $event)
                ->with('status', 'Event tidak dapat diedit pada status ini.');
        }

        return view('eo.events.edit', [
            'event' => $event,
            'venues' => Venue::query()->orderBy('name')->get(),
        ]);
    }

    public function update(StoreEventRequest $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        if (! in_array($event->status, ['DRAFT', 'REJECTED'], true)) {
            return to_route('eo.events.show', $event)
                ->with('status', 'Event tidak dapat diedit pada status ini.');
        }

        if ($request->hasFile('banner') && $event->banner_url) {
            $this->deleteImageUrl($event->banner_url);
        }

        $event->update($this->eventPayload($request->validated(), $request));

        return to_route('eo.events.show', $event)
            ->with('status', 'Event berhasil diperbarui.');
    }

    public function submit(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        if (! in_array($event->status, ['DRAFT', 'REJECTED'], true)) {
            return to_route('eo.events.show', $event)
                ->with('status', 'Event tidak dapat diajukan pada status ini.');
        }

        $event->update([
            'status' => 'PENDING_APPROVAL',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => null,
        ]);

        return to_route('eo.events.show', $event)
            ->with('status', 'Event berhasil diajukan ke admin untuk persetujuan.');
    }

    public function cancel(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        if (! in_array($event->status, ['DRAFT', 'PENDING_APPROVAL'], true)) {
            return to_route('eo.events.show', $event)
                ->with('status', 'Event tidak dapat dibatalkan pada status ini.');
        }

        $event->update(['status' => 'CANCELLED']);

        return to_route('eo.events.index')
            ->with('status', 'Event berhasil dibatalkan.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function eventPayload(array $validated, Request $request): array
    {
        $venueType = $validated['venue_type'];
        $payload = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
            'venue_type' => $venueType,
            'venue_id' => $venueType === 'INTERNAL' ? $validated['venue_id'] : null,
            'external_venue_name' => $venueType === 'EXTERNAL' ? $validated['external_venue_name'] : null,
            'external_venue_address' => $venueType === 'EXTERNAL' ? ($validated['external_venue_address'] ?? null) : null,
            'external_venue_capacity' => $venueType === 'EXTERNAL' ? ($validated['external_venue_capacity'] ?? null) : null,
            'event_start' => $validated['event_start'],
            'event_end' => $validated['event_end'],
            'registration_deadline' => $validated['registration_deadline'] ?? null,
            'slot_size' => $validated['slot_size'] ?? 0,
            'capacity' => $validated['capacity'] ?? 0,
        ];

        if ($request->hasFile('banner')) {
            $payload['banner_url'] = $this->uploadImage($request->file('banner'), 'event-banners');
        }

        return $payload;
    }
}
