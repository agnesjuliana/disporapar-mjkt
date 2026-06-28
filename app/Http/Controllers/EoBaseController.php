<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventOrganizer;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

abstract class EoBaseController extends Controller
{
    protected function resolveOrganizer(Request $request): EventOrganizer
    {
        return EventOrganizer::forUserOrCreate($request->user());
    }

    protected function authorizeEvent(Request $request, Event $event): void
    {
        $organizer = $this->resolveOrganizer($request);
        abort_unless($event->organizer_id === $organizer->id, 403);
    }

    protected function abortUnlessEventRegistration(Event $event, EventRegistration $registration): void
    {
        abort_unless($registration->event_id === $event->id, 404);
    }
}
