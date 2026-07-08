<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventOrganizer;
use App\Models\EventSlot;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredEvents = Event::query()
            ->with(['organizer', 'venue'])
            ->withEventCounts()
            ->where('status', 'APPROVED')
            ->where('event_start', '>=', now())
            ->orderBy('event_start')
            ->take(6)
            ->get();

        $stats = [
            [
                'label' => 'Event Aktif',
                'value' => Event::query()->where('status', 'APPROVED')->count(),
                'icon' => 'calendar-check',
            ],
            [
                'label' => 'Event Organizer',
                'value' => EventOrganizer::query()->count(),
                'icon' => 'users',
            ],
            [
                'label' => 'Slot Tenant Tersedia',
                'value' => EventSlot::query()->where('is_booked', false)->count(),
                'icon' => 'store',
            ],
            [
                'label' => 'Venue Resmi',
                'value' => Venue::query()->count(),
                'icon' => 'map-pin',
            ],
        ];

        return view('welcome', [
            'featuredEvents' => $featuredEvents,
            'stats' => $stats,
        ]);
    }

    public function events(Request $request): View
    {
        $search = $request->string('search')->toString();

        $events = Event::query()
            ->with(['organizer', 'venue'])
            ->withEventCounts()
            ->where('status', 'APPROVED')
            ->search($search)
            ->orderBy('event_start')
            ->paginate(9)
            ->withQueryString();

        return view('landing.events', [
            'events' => $events,
            'search' => $search,
        ]);
    }
}
