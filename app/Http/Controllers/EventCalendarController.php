<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventCalendarController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $selectedDate = $this->parseDate($request->string('date')->toString()) ?? today();
        $month = $this->parseMonth($request->string('month')->toString()) ?? $selectedDate->copy()->startOfMonth();

        $calendarStart = $month->copy()->startOfMonth()->startOfWeek();
        $calendarEnd = $month->copy()->endOfMonth()->endOfWeek();

        $monthEvents = Event::query()
            ->with(['organizer', 'venue'])
            ->whereIn('status', ['APPROVED', 'ONGOING'])
            ->where('event_start', '<=', $calendarEnd->copy()->endOfDay())
            ->where('event_end', '>=', $calendarStart->copy()->startOfDay())
            ->orderBy('event_start')
            ->get();

        $eventCounts = [];
        foreach ($monthEvents as $event) {
            $period = CarbonPeriod::create(
                $event->event_start->copy()->max($calendarStart)->startOfDay(),
                $event->event_end->copy()->min($calendarEnd)->startOfDay()
            );

            foreach ($period as $date) {
                $key = $date->format('Y-m-d');
                $eventCounts[$key] = ($eventCounts[$key] ?? 0) + 1;
            }
        }

        $events = Event::query()
            ->with(['organizer', 'venue'])
            ->whereIn('status', ['APPROVED', 'ONGOING'])
            ->where('event_start', '<=', $selectedDate->copy()->endOfDay())
            ->where('event_end', '>=', $selectedDate->copy()->startOfDay())
            ->when($search, fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('event_start')
            ->paginate(9)
            ->withQueryString();

        $registeredEventIds = $request->user()?->role === 'MASYARAKAT'
            ? $request->user()
                ->participantRegistrations()
                ->whereIn('event_id', $events->getCollection()->pluck('id'))
                ->pluck('event_id')
                ->all()
            : [];

        return view('masyarakat.calendar.index', [
            'events' => $events,
            'eventCounts' => $eventCounts,
            'weeks' => collect(iterator_to_array(CarbonPeriod::create($calendarStart, $calendarEnd)))->chunk(7),
            'calendarStart' => $calendarStart,
            'calendarEnd' => $calendarEnd,
            'month' => $month,
            'selectedDate' => $selectedDate,
            'search' => $search,
            'registeredEventIds' => $registeredEventIds,
        ]);
    }

    private function parseDate(string $date): ?Carbon
    {
        if ($date === '') {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    private function parseMonth(string $month): ?Carbon
    {
        if ($month === '') {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable) {
            return null;
        }
    }
}
