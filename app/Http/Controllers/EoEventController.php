<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventOrganizer;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EoEventController extends Controller
{
    public function index(Request $request): View
    {
        $organizer = EventOrganizer::forUserOrCreate($request->user());
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

    public function store(Request $request): RedirectResponse
    {
        $organizer = EventOrganizer::forUserOrCreate($request->user());
        $validated = $this->validatedEvent($request);

        $event = Event::create([
            'id' => (string) Str::uuid(),
            'organizer_id' => $organizer->id,
            'status' => 'DRAFT',
            ...$this->eventPayload($validated, $request),
        ]);

        return to_route('eo.events.show', $event)
            ->with('status', "Event '{$event->name}' berhasil dibuat sebagai draft.");
    }

    public function show(Request $request, Event $event): View
    {
        $organizer = EventOrganizer::forUserOrCreate($request->user());
        abort_unless($event->organizer_id === $organizer->id, 403);

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
        $organizer = EventOrganizer::forUserOrCreate($request->user());
        abort_unless($event->organizer_id === $organizer->id, 403);

        if (! in_array($event->status, ['DRAFT', 'REJECTED'], true)) {
            return to_route('eo.events.show', $event)
                ->with('status', 'Event tidak dapat diedit pada status ini.');
        }

        return view('eo.events.edit', [
            'event' => $event,
            'venues' => Venue::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $organizer = EventOrganizer::forUserOrCreate($request->user());
        abort_unless($event->organizer_id === $organizer->id, 403);

        if (! in_array($event->status, ['DRAFT', 'REJECTED'], true)) {
            return to_route('eo.events.show', $event)
                ->with('status', 'Event tidak dapat diedit pada status ini.');
        }

        $validated = $this->validatedEvent($request);

        if ($request->hasFile('banner') && $event->banner_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $event->banner_url));
        }

        $event->update($this->eventPayload($validated, $request));

        return to_route('eo.events.show', $event)
            ->with('status', 'Event berhasil diperbarui.');
    }

    public function submit(Request $request, Event $event): RedirectResponse
    {
        $organizer = EventOrganizer::forUserOrCreate($request->user());
        abort_unless($event->organizer_id === $organizer->id, 403);

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
        $organizer = EventOrganizer::forUserOrCreate($request->user());
        abort_unless($event->organizer_id === $organizer->id, 403);

        if (! in_array($event->status, ['DRAFT', 'PENDING_APPROVAL'], true)) {
            return to_route('eo.events.show', $event)
                ->with('status', 'Event tidak dapat dibatalkan pada status ini.');
        }

        $event->update(['status' => 'CANCELLED']);

        return to_route('eo.events.index')
            ->with('status', 'Event berhasil dibatalkan.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedEvent(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'venue_type' => ['required', Rule::in(['INTERNAL', 'EXTERNAL'])],
            'venue_id' => ['nullable', 'required_if:venue_type,INTERNAL', 'exists:venues,id'],
            'external_venue_name' => ['nullable', 'required_if:venue_type,EXTERNAL', 'string', 'max:255'],
            'external_venue_address' => ['nullable', 'string', 'max:255'],
            'external_venue_capacity' => ['nullable', 'integer', 'min:0'],
            'event_start' => ['required', 'date'],
            'event_end' => ['required', 'date', 'after:event_start'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:event_start'],
            'slot_size' => ['nullable', 'integer', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'banner' => ['nullable', 'image', 'max:2048'],
        ]);
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
            $payload['banner_url'] = '/storage/'.$request->file('banner')->store('event-banners', 'public');
        }

        return $payload;
    }
}

