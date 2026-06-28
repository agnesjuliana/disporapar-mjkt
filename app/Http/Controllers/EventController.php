<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $events = Event::query()
            ->with(['organizer', 'venue'])
            ->withEventCounts()
            ->where('status', 'APPROVED')
            ->search($search)
            ->orderByDesc('event_start')
            ->paginate(9)
            ->withQueryString();

        return view('tenant.events.index', [
            'events' => $events,
            'search' => $search,
        ]);
    }

    public function show(Request $request, Event $event): View
    {
        abort_unless($event->status === 'APPROVED', 404);

        $tenant = Tenant::query()->where('user_id', $request->user()->id)->first();

        $alreadyRegistered = $tenant
            ? EventRegistration::query()
                ->where('tenant_id', $tenant->id)
                ->where('event_id', $event->id)
                ->where('registration_status', '!=', 'CANCELLED')
                ->exists()
            : false;

        $event->load(['organizer', 'venue'])->loadCount([
            'slots',
            'slots as booked_slots_count' => fn (Builder $query) => $query->where('is_booked', true),
        ]);

        return view('tenant.events.show', [
            'event' => $event,
            'tenant' => $tenant,
            'alreadyRegistered' => $alreadyRegistered,
        ]);
    }
}
